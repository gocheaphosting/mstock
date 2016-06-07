<?
include("../admin/function/db.php");
include("payments_settings.php");

if ($_POST) 
{
	header("HTTP/1.0 200 OK");
	
	$eshopId        = trim(stripslashes($_POST['eshopId']));            
    $orderId        = trim(stripslashes($_POST['orderId']));            
    $serviceName    = trim(stripslashes($_POST['serviceName']));        
    $eshopAccount   = trim(stripslashes($_POST['eshopAccount']));       
    $recipientAmount= trim(stripslashes($_POST['recipientAmount']));    
    $recipientCurrency=trim(stripslashes($_POST['recipientCurrency'])); 
    $paymentStatus  = trim(stripslashes($_POST['paymentStatus']));      
    $userName       = trim(stripslashes($_POST['userName']));           
    $userEmail      = trim(stripslashes($_POST['userEmail']));          
    $paymentData    = trim(stripslashes($_POST['paymentData']));        
    $secretKey      = trim(stripslashes($_POST['secretKey']));          
    $hash           = trim(stripslashes($_POST['hash']));               
    
    
    $control_hash=strtolower(md5($eshopId."::".$orderId."::".$serviceName."::".$eshopAccount."::".$recipientAmount."::".$recipientCurrency."::".$paymentStatus."::".$userName."::".$userEmail."::".$paymentData."::".$site_rbkmoney_password));
    
    if($hash == $control_hash)
    {
    	if($paymentStatus==5)
    	{
    		$rbk=explode("-",$orderId);
    		$product_type=$rbk[0];
    		$id=(int)$rbk[1];
    		$transaction_id=transaction_add("rbkmoney",(int)$_POST["paymentId"],result($product_type),$id);
    		
    		
				
				

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