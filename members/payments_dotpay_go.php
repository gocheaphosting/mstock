<?
include("../admin/function/db.php");
include("payments_settings.php");

echo "OK";

if ($_POST and $_POST["status"]=="OK") 
{
	$md5 = md5($site_dotpay_password.":"
		  .$_POST['id'].":"
		  .$_POST['control'].":"
		  .$_POST['t_id'].":"
		  .$_POST['amount'].":"
		  .$_POST['email'].":"
		  .$_POST['service'].":"
		  .$_POST['code'].":"
		  .$_POST['username'].":"
		  .$_POST['password'].":"
		  .$_POST['t_status']);
		  
	$ip = $_SERVER['REMOTE_ADDR'];
	$dotpay_ip = "195.150.9.37";
    
    if($md5 == $_POST["md5"] and $site_dotpay_account == $_POST['id'] and $ip == $dotpay_ip)
    {
    	if($_POST["t_status"]==2)
    	{
    		$mass=explode("-",result($_POST["description"]));
    		$product_type=$mass[0];
    		$id=(int)$mass[1];
    		$transaction_id=transaction_add("dotpay",(int)$_POST["t_id"],result($product_type),$id);

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
?>