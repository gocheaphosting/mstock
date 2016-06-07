<?
include("../admin/function/db.php");
include("payments_settings.php");

if($site_mellatbank_account=="" or $site_mellatbank_account2=="")
{
	exit();
}

require_once("../admin/plugins/mellatbank/nusoap.php");

$client = new soapclient('https://pgwstest.bpm.bankmellat.ir/pgwchannel/services/pgw?wsdl');
$namespace='http://interfaces.core.sw.bps.com/';

if ($_POST) 
{

		$terminalId = $site_mellatbank_account2;
		$userName = $site_mellatbank_account;
		$userPassword =$site_mellatbank_password;
		$orderId = $_POST['SaleOrderId'];
		$verifySaleOrderId = $_POST['SaleOrderId'];
		$verifySaleReferenceId = $_POST['SaleReferenceId'];

		// Check for an error
		$err = $client->getError();
		if ($err) {
			echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
			die();
		}
	  	  
		$parameters = array(
			'terminalId' => $terminalId,
			'userName' => $userName,
			'userPassword' => $userPassword,
			'orderId' => $orderId,
			'saleOrderId' => $verifySaleOrderId,
			'saleReferenceId' => $verifySaleReferenceId);

		// Call the SOAP method
		$result = $client->call('bpVerifyRequest', $parameters, $namespace);

		// Check for a fault
		if ($client->fault) {
			echo '<h2>Fault</h2><pre>';
			print_r($result);
			echo '</pre>';
			die();
		} 
		else {

			$resultStr = $result;
			
			$err = $client->getError();
			if ($err) {
				// Display the error
				echo '<h2>Error</h2><pre>' . $err . '</pre>';
				die();
			} 
			else {
				// Display the result
				// Update Table, Save Verify Status 
				// Note: Successful Verify means complete successful sale was done.
				echo "<script>alert('Verify Response is : " . $resultStr . "');</script>";
				echo "Verify Response is : " . $resultStr;
				if($resultStr==0)
				{
    		$mass=explode("-",result($_POST['SaleOrderId']));
    		$product_type=$mass[0];
    		$id=(int)$mass[1];
    		$transaction_id=transaction_add("mellatbank",result($_POST['SaleReferenceId']),result($product_type),$id);
    		
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
			}// end Display the result
		}// end Check for errors

}


$db->close();
?>