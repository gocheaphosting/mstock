<?
include("../admin/function/db.php");
include("payments_settings.php");
$site="myvirtualmerchant";
if($site_myvirtualmerchant_account!="")
{
		if($site_myvirtualmerchant_ipn==true)
		{
			
			if($_POST["result"]==0 and $_POST["ssl_result_message"]=="APPROVAL")
			{
				$product_mass=explode("-",$_POST["ssl_invoice_number"]);
				
				$product_id=(int)$product_mass[0];
				$product_type=$product_mass[1];

					if($product_type=="credits")
					{
						$sql="select id_parent from credits_list where id_parent=".(int)$product_id;
						$ds->open($sql);
						if(!$ds->eof)
						{
							$transaction_id=transaction_add("myvirtualmerchant",result($_POST["ssl_txn_id"]),"credits",$product_id);
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
							$transaction_id=transaction_add("myvirtualmerchant",result($_POST["ssl_txn_id"]),"subscription",$product_id);
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
							$transaction_id=transaction_add("myvirtualmerchant",result($_POST["ssl_txn_id"]),"order",$product_id);
							order_approve($product_id);
							commission_add($product_id);

							coupons_add(order_user($product_id));
							send_notification('neworder_to_user',$product_id);
							send_notification('neworder_to_admin',$product_id);
						}	
					}
			}
		}
}
?>
<?include("../inc/header.php");?>

<h1><?=word_lang("payment")?> - MyVirtualMerchant</h1>

<p>Thank you! Your transaction has been sent successfully.</p>

<?include("../inc/footer.php");?>