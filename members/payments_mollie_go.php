<?
include("../admin/function/db.php");
include("payments_settings.php");

if($_POST) 
{
 	try
	{ 	
 		$api_key=$site_mollie_account;
 		include "../admin/plugins/mollie/examples/initialize.php";
 		
 		$payment  = $mollie->payments->get($_POST["id"]);
 		$order_id = $payment->metadata->order_id;
 	
    	if ($payment->isPaid() == TRUE)
		{	
    		$mass=explode("-",$order_id);
    		$product_type=$mass[0];
    		$id=(int)$mass[1];
    		$transaction_id=transaction_add("mollie","",result($product_type),$id);	

			if($product_type=="credits")
			{
				credits_approve($id,$transaction_id);
				send_notification('credits_to_user',$id);
				send_notification('credits_to_admin',$id);
			}

			if($product_type=="subscription")
			{
				subscription_approve($id);
				send_notification('subscription_to_user',$id);
				send_notification('subscription_to_admin',$id);
			}

			if($product_type=="order")
			{
				order_approve($id);
				commission_add($id);

				coupons_add(order_user($id));
				send_notification('neworder_to_user',$id);
				send_notification('neworder_to_admin',$id);
			}
   		}
   	}
	catch (Mollie_API_Exception $e)
	{
		echo "API call failed: " . htmlspecialchars($e->getMessage());
	}
}

$db->close();
?>