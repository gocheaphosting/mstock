<?
include("../admin/function/db.php");
include("payments_settings.php");
require_once('../admin/plugins/paysera/WebToPay.php');


try {
    $response = WebToPay::checkResponse($_GET, array(
        'projectid'     => 0,
        'sign_password' => 'd41d8cd98f00b204e9800998ecf8427e',
    ));

    if ($response['test'] !== '0') {
        throw new Exception('Testing, real payment was not made');
    }
    if ($response['type'] !== 'macro') {
        throw new Exception('Only macro payment callbacks are accepted');
    }


	echo("OK");
    		
    $mass=explode("-",$response['orderid']);
    $product_type=$mass[0];
    $id=(int)$mass[1];
    $transaction_id=transaction_add("paysera","",result($product_type),$id);	

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
			
} catch (Exception $e) {
    echo get_class($e) . ': ' . $e->getMessage();
}



$db->close();
    		

?>