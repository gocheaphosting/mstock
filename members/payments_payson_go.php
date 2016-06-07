<?
include("../admin/function/db.php");
include("payments_settings.php");

if($site_payson_account=="")
{
	exit();
}


require_once '../admin/plugins/payson/paysonapi.php';

// Your agent ID and md5 key
$agentID = $site_payson_account2;
$md5Key = $site_payson_password;

// Get the POST data
$postData = file_get_contents("php://input");



// Set up API
$credentials = new PaysonCredentials($agentID, $md5Key);
$api = new PaysonApi($credentials, TRUE);

// Validate the request
$response = $api->validate($postData);


if ($response->isVerified()) {
    // IPN request is verified with Payson
    // Check details to find out what happened with the payment
    $details = $response->getPaymentDetails();

    // After we have checked that the response validated we have to check the actual status 
    // of the transfer
    if ($details->getType() == "TRANSFER" && $details->getStatus() == "COMPLETED") {
        // Handle completed card & bank transfers here
        
        
     
        $mass=explode("-",result($_POST["orderItemList.orderItem(0).description"]));
    		$product_type=$mass[0];
    		$id=(int)$mass[1];
    		$transaction_id=transaction_add("payson",(int)$details->getPurchaseId(),result($product_type),$id);

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
        
        
    } elseif ($details->getType() == "INVOICE" && $details->getStatus() == "PENDING" && $details->getInvoiceStatus() == "ORDERCREATED") {
        // Handle accepted invoice purchases here
    } else if ($details->getStatus() == "ERROR") {
        // Handle errors here
    }
    /*
      //More info
      $response->getPaymentDetails()->getCustom();
      $response->getPaymentDetails()->getShippingAddressName();
      $response->getPaymentDetails()->getShippingAddressStreetAddress();
      $response->getPaymentDetails()->getShippingAddressPostalCode();
      $response->getPaymentDetails()->getShippingAddressCity();
      $response->getPaymentDetails()->getShippingAddressCountry();
      $response->getPaymentDetails()->getToken();
      $response->getPaymentDetails()->getType();
      $response->getPaymentDetails()->getStatus();
      $response->getPaymentDetails()->getCurrencyCode();
      $response->getPaymentDetails()->getTrackingId();
      $response->getPaymentDetails()->getCorrelationId();
      $response->getPaymentDetails()->getPurchaseId();
      $response->getPaymentDetails()->getSenderEmail();
      $response->getPaymentDetails()->getInvoiceStatus();
      $response->getPaymentDetails()->getGuaranteeStatus();
      $details->getReceiverFee();
     */
}




$db->close();
?>