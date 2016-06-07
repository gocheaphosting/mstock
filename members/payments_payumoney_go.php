<?
include("../admin/function/db.php");
include("payments_settings.php");


$status=$_POST["status"];
$firstname=$_POST["firstname"];
$amount=$_POST["amount"];
$txnid=$_POST["txnid"];
$posted_hash=$_POST["hash"];
$key=$_POST["key"];
$productinfo=$_POST["productinfo"];
$email=$_POST["email"];
$salt=$site_payumoney_password;

If (isset($_POST["additionalCharges"])) {
       $additionalCharges=$_POST["additionalCharges"];
        $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        
                  }
	else {	  

        $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;

         }
		 $hash = hash("sha512", $retHashSeq);
		 
       if ($hash != $posted_hash) {
	       echo "Invalid Transaction. Please try again";
		   }
	   else {
	   
	   
	   		$crc=explode("-",$_POST["txnid"]);
    		$id=(int)$crc[1];
    		$product_type=result($crc[0]);

			$transaction_id=transaction_add("payumoney","",$product_type,$id);
	
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
           	   
          echo "<h3>Thank You. Your order status is ". $status .".</h3><meta http-equiv='refresh' content=\"3; url=".site_root."/members/profile_home.php\">";
          
		   }         





$db->close();
?>