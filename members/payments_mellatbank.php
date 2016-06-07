<?if(!defined("site_root")){exit();}?>
<?
if($site_mellatbank_account=="" or $site_mellatbank_account2=="")
{
	exit();
}

require_once("../admin/plugins/mellatbank/nusoap.php");

$client = new soapclient('https://pgwstest.bpm.bankmellat.ir/pgwchannel/services/pgw?wsdl');
$namespace='http://interfaces.core.sw.bps.com/';

		$terminalId = $site_mellatbank_account2;
		$userName = $site_mellatbank_account;
		$userPassword =$site_mellatbank_password;
		$orderId = $product_type."-".$product_id;
		$amount = $product_total;
		$localDate = date("Ymd");
		$localTime = date("His");
		$additionalData = $product_name;
		$callBackUrl = surl.site_root."/members/payments_mellatbank_go.php";
		$payerId = 0;

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
			'amount' => $amount,
			'localDate' => $localDate,
			'localTime' => $localTime,
			'additionalData' => $additionalData,
			'callBackUrl' => $callBackUrl,
			'payerId' => $payerId);

		// Call the SOAP method
		$result = $client->call('bpPayRequest', $parameters, $namespace);
		
		// Check for a fault
		if ($client->fault) {
			echo '<h2>Fault</h2><pre>';
			print_r($result);
			echo '</pre>';
			die();
		} 
		else {
			// Check for errors
			
			$resultStr  = $result;

			$err = $client->getError();
			if ($err) {
				// Display the error
				echo '<h2>Error</h2><pre>' . $err . '</pre>';
				die();
			} 
			else {
				// Display the result

				$res = explode (',',$resultStr);

				//echo "<script>alert('Pay Response is : " . $resultStr . "');</script>";
				//echo "Pay Response is : " . $resultStr;

				$ResCode = $res[0];
				
				if ($ResCode == "0") {
					// Update table, Save RefId
					//echo "<script language='javascript' type='text/javascript'>postRefId('" . $res[1] . "');</script>";
					?>
					<form action="https://pgwtest.bpm.bankmellat.ir/pgwchannel/startpay.mellat" method="post"  name="process" id="process">
					<input type="hidden" name="RefId" value="<?=$res[1]?>">
					</form>
					<?
				} 
				else {
				// log error in app
					// Update table, log the error
					// Show proper message to user
				}
			}// end Display the result
		}// end Check for errors
?>