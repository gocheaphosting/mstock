<?
include("../admin/function/db.php");
include("payments_settings.php");


include("../admin/plugins/checkout.fi/CheckoutFinland/Response.php");

$demo_merchant_secret   = $site_checkoutfi_password;

$response = new Response($demo_merchant_secret);

$response->setRequestParams($_GET);

$status_string = '';


    if($response->validate()) {
        // we have a valid response, now check the status

        // the status codes are listed in the api documentation of Checkout Finland
        switch($response->getStatus())
        {
            case '2':
            case '5':
            case '6':
            case '8':
            case '9':
            case '10':
                // These are paid and we can ship the product
                $status_string = 'PAID';
                break;
            case '7':
            case '3':
            case '4':
                // Payment delayed or it is not known yet if the payment was completed 
                 $status_string = 'DELAYED';
                break;
            case '-1':
                 $status_string = 'CANCELLED BY USER';
                 break;
            case '-2':
            case '-3':
            case '-4':
            case '-10':
                // Cancelled by banks, Checkout Finland, time out e.g. 
                 $status_string = 'CANCELLED';
                break;
        }

    } else {
        // something went wrong with the validation, perhaps the user changed the return parameters
    }

	   
if($status_string=="PAID")
{
	   		$crc=explode("-",$_REQUEST["reference"]);
    		$id=(int)$crc[1];
    		$product_type=result($crc[0]);

			$transaction_id=transaction_add("checkoutfi","",$product_type,$id);
	
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



$db->close();
?>