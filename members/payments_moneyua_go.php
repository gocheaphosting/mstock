<?
include("../admin/function/db.php");
include("payments_settings.php");

if($_POST) 
{
 	$control_hash=md5($_POST["RETURN_MERCHANT"].":".$_POST["RETURN_ADDVALUE"].":".$_POST["RETURN_CLIENTORDER"].":".$_POST["RETURN_AMOUNT"].":".$_POST["RETURN_COMISSION"].":".$_POST["RETURN_UNIQ_ID"].":".$_POST["TEST_MODE"].":".$_POST["PAYMENT_DATE"].":".$site_moneyua_password.":".$_POST["RETURN_RESULT"]);
 	
    if($_POST["RETURN_HASH"] == $control_hash)
    {
    	if($_POST["RETURN_RESULT"]==20)
    	{
    		echo("OK");
    		
    		$mass=explode("-",$_POST["RETURN_CLIENTORDER"]);
    		$product_type=$mass[0];
    		$id=(int)$mass[1];
    		$transaction_id=transaction_add("moneyua",(int)$_POST["RETURN_UNIQ_ID"],result($product_type),$id);	

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