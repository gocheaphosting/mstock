<?
include("../admin/function/db.php");
include("payments_settings.php");



if ($_POST) 
{
	$sig = md5 ($site_payu_account . $_POST["session_id"] . $_POST["ts"] . $site_payu_password2 );
	
	$sig2 = md5 ($site_payu_account . $_POST["session_id"] . $_POST["ts"] . $site_payu_password );
	
	echo("OK");

    
    if($sig == $_POST["sig"])
    {
    	
    	$postdata="pos_id=".$site_payu_account."&session_id=".$_POST["session_id"]."&ts=".$_POST["ts"]."&sig=".$sig2; 


 			$curl = curl_init("https://secure.payu.com/paygw/UTF/Payment/get"); 
 			
  			curl_setopt ($curl, CURLOPT_HEADER, 0); 
  			curl_setopt ($curl, CURLOPT_POST, 1); 
  			curl_setopt ($curl, CURLOPT_POSTFIELDS, $postdata); 
 			curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0); 
  			curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1); 
  			curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 1); 
  			/*
  			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close')); 
			*/
  			$response = curl_exec ($curl); 
  			curl_close ($curl); 
  			//echo($response);

    	
    	
    	if(preg_match("/trans_status:99/",$response) or preg_match("/<status>99<\/status>/",$response))
    	{
    		$mass=explode("-",result($_POST["session_id"]));
    		$product_type=$mass[0];
    		$id=(int)$mass[1];
    		$transaction_id=transaction_add("payu","",result($product_type),$id);

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