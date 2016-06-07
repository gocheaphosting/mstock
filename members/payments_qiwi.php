<?if(!defined("site_root")){exit();}?>
<?
if($site_qiwi_account!="")
{
	if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
	{
		if($site_qiwi_ipn==true)
		{
			$i = file_get_contents('php://input');

			$l = array('/<login>(.*)?<\/login>/', '/<password>(.*)?<\/password>/');
			$s = array('/<txn>(.*)?<\/txn>/', '/<status>(.*)?<\/status>/');

			preg_match($l[0], $i, $m1);
			preg_match($l[1], $i, $m2);

			preg_match($s[0], $i, $m3);
			preg_match($s[1], $i, $m4);
			
			$password = $site_qiwi_code;
			$hash = strtoupper(md5($m3[1].strtoupper(md5($password))));
			
			if($hash !== $m2[1])
			{
				$resultCode=150;
			}
			else
			{
				$resultCode=0;
				
				$product_mass=explode("-",$m3[1]);
				
				$product_id=(int)$product_mass[0];
				$product_type=$product_mass[1];

					if($product_type=="credits")
					{
						$sql="select id_parent from credits_list where id_parent=".(int)$product_id;
						$ds->open($sql);
						if(!$ds->eof)
						{
							$transaction_id=transaction_add("qiwi",(int)$m4[1],"credits",$product_id);
							credits_approve($product_id,$transaction_id);
							send_notification('credits_to_user',$product_id);
							send_notification('credits_to_admin',$product_id);
						}
					}

					if($product_type=="subscription")
					{
						$sql="select id_parent from subscription_list where id_parent=".(int)$product_id;
						$ds->open($sql);
						if(!$ds->eof)
						{
							$transaction_id=transaction_add("qiwi",(int)$m4[1],"subscription",$product_id);
							subscription_approve($product_id);
							send_notification('subscription_to_user',$product_id);
							send_notification('subscription_to_admin',$product_id);
						}
					}

					if($product_type=="order")
					{
						$sql="select id from orders where id=".(int)$product_id;
						$ds->open($sql);
						if(!$ds->eof)
						{
							$transaction_id=transaction_add("qiwi",(int)$m4[1],"order",$product_id);
							order_approve($product_id);
							commission_add($product_id);

							coupons_add(order_user($product_id));
							send_notification('neworder_to_user',$product_id);
							send_notification('neworder_to_admin',$product_id);
						}	
					}
					if($product_type=="payout_seller" or $product_type=="payout_affiliate")
					{
						payout_approve((int)$product_id,$product_type);
					}
			}
			
			$text = "<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ns1=\"http://client.ishop.mw.ru/\"><SOAP-ENV:Body><ns1:updateBillResponse><updateBillResult>status</updateBillResult></ns1:updateBillResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>";
			$text = str_replace('status',$resultCode , $text);
			header('content-type: text/xml; charset=UTF-8');
    		echo $text;
    		exit();
		}
	}
	else
	{
		$telephone="";
		
		if(isset($_POST["telephone"]))
		{
			$telephone=result($_POST["telephone"]);
		}
		
		if(isset($_GET["telephone"]))
		{
			$telephone=result($_GET["telephone"]);
		}
	
		?>
		<form method="post" action="http://w.qiwi.ru/setInetBill_utf.do" name="process" id="process">
		<input type="hidden" name="from" value="<?=$site_qiwi_account?>" />
		<input type="hidden" name="to" value="<?=$telephone?>" />
		<input type="hidden" name="txn_id" value="<?=$product_id?>-<?=$product_type?>" />
		<input type="hidden" name="summ" value="<?=$product_total?>" />
		<input type="hidden" name="com" value="<?=$product_name?>" />
		<input type="hidden" name="lifetime" value="1080" />
		<input type="hidden" name="check_agt" value="true" />
		</form>
		<?
	}
}
?>
