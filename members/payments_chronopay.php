<?if(!defined("site_root")){exit();}?>
<?
if($site_chronopay_account!="")
{
	if(isset($_GET["mode"]) and $_GET["mode"]=="notification" and isset($_POST["transaction_type"]) and $_POST["transaction_type"]=="Purchase")
	{
		if($site_chronopay_ipn==true)
		{
			$sign = md5($site_chronopay_code . $_POST['customer_id'] . $_POST['transaction_id'] . $_POST['transaction_type'] . $_POST['total']);
			
			if($sign==$_POST['sign'] and ($_SERVER["REMOTE_ADDR"]=="207.97.254.211" or $_SERVER["REMOTE_ADDR"]=="159.255.220.140"))
			{
				$product_id=(int)$_POST['order_id'];

					$sql="select id_parent from credits_list where id_parent=".(int)$product_id;
					$ds->open($sql);
					if(!$ds->eof)
					{
						$transaction_id=transaction_add("chronopay",$_POST['transaction_id'],"credits",$product_id);
						credits_approve($product_id,$transaction_id);
						send_notification('credits_to_user',$product_id);
						send_notification('credits_to_admin',$product_id);
					}

					$sql="select id_parent from subscription_list where id_parent=".(int)$product_id;
					$ds->open($sql);
					if(!$ds->eof)
					{
						$transaction_id=transaction_add("chronopay",$_POST['transaction_id'],"subscription",$product_id);
						subscription_approve($product_id);
						send_notification('subscription_to_user',$product_id);
						send_notification('subscription_to_admin',$product_id);
					}

					$sql="select id from orders where id=".(int)$product_id;
					$ds->open($sql);
					if(!$ds->eof)
					{
						$transaction_id=transaction_add("chronopay",$_POST['transaction_id'],"order",$product_id);
						order_approve($product_id);
						commission_add($product_id);

						coupons_add(order_user($product_id));
						send_notification('neworder_to_user',$product_id);
						send_notification('neworder_to_admin',$product_id);
					}
					
					echo("200 ÎÊ");
			}
		}
	}
	else
	{
		$sign = md5($site_chronopay_account . '-' . $product_total . '-' . $site_chronopay_code);
	?>
		<form method="post" action="https://payments.chronopay.com/" name="process" id="process">
		<input type="hidden" name="product_id" value="<?=$site_chronopay_account?>" />
		<input type="hidden" name="product_name" value="<?=$product_name?>" />
		<input type="hidden" name="product_price" value="<?=$product_total?>" />
		<input type="hidden" name="product_price_currency" value="<?=$currency_code1?>" />
		<?
			$sql="select name,email,telephone,address,country,city from users where id_parent=".(int)$_SESSION["people_id"];
			$dr->open($sql);
			if(!$dr->eof)
			{
			?>
				<input type="hidden" name="f_name" value="<?=$dr->row["name"]?>" />
				<input type="hidden" name="s_name" value="" />
				<input type="hidden" name="street" value="<?=$dr->row["address"]?>" />
				<input type="hidden" name="city" value="<?=$dr->row["city"]?>" />
				<input type="hidden" name="state" value="" />
				<input type="hidden" name="zip" value="" />
				<input type="hidden" name="country" value="<?=$dr->row["country"]?>" />
				<input type="hidden" name="phone" value="<?=$dr->row["telephone"]?>" />
				<input type="hidden" name="email" value="<?=$dr->row["email"]?>" />
			<?
			}
		?>
		<input type="hidden" name="cb_url" value="<?=surl?><?=site_root?>/members/payments_process.php?mode=notification&processor=chronopay" />
		<input type="hidden" name="cb_type" value="P" />
		<input type="hidden" name="success_url" value="<?=surl?><?=site_root?>/members/payments.php?d=1" />
		<input type="hidden" name="decline_url" value="<?=surl?><?=site_root?>/members/payments.php?d=2" />
		<input type="hidden" name="order_id" value="<?=$product_id?>" />
		<input type="hidden" name="sign" value="<?=$sign?>" /> 
		</form>
		<?
	}
}
?>
