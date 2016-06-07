<?
include("../admin/function/db.php");
include("payments_settings.php");

if($_POST) 
{
 	$signature = base64_encode( sha1( $site_privatbank_password .  $_POST["data"] . $site_privatbank_password , 1 ));
 	
 	$results=json_decode(base64_decode($_POST["data"]));
 	
    if($_POST["signature"] == $signature)
    {
    	if($results -> status == "success" or $results -> status == "sandbox")
    	{	
    		$mass=explode("-",$results -> order_id);
    		$product_type=$mass[0];
    		$id=(int)$mass[1];
    		$transaction_id=transaction_add("privatbank",$results -> payment_id,result($product_type),$id);	

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
}

$db->close();
?>