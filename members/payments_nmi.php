<?
$site="nmi";
include("../admin/function/db.php");



include("payments_settings.php");
?>
<?include("../inc/header.php");?>

<h1><?=word_lang("payment")?> - Network Merchants</h1>

<?
$test_mode=true;
if(isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"]=="on")
{
	$test_mode=false;
}

if($test_mode)
{
	echo("<div class='warning'>Error. The payment method requires a secure ssl connection. The transaction will be in <b>TEST MODE</b>. Please not to use valid credit card details!</div>");
}
?>


<?



$gatewayURL = 'https://secure.networkmerchants.com/api/v2/three-step';
$APIKey = $site_nmi_account;


?>



<?
if(!isset($_GET['token-id']) or empty($_GET['token-id']))
{

$product_id=(int)$_REQUEST["product_id"];
$product_name=result($_REQUEST["product_name"]);
$product_total=$_REQUEST["product_total"];
$product_type=result($_REQUEST["product_type"]);

$buyer_info=array();
get_buyer_info($_SESSION["people_id"],$product_id,$product_type);

$order_info=array();
get_order_info($product_id,$product_type);
?>


<div class='login_header'><h2 style="margin-top:30px"><?=word_lang("credit card")?>:</h2></div>





<?
    // Initiate Step One: Now that we've collected the non-sensitive payment information, we can combine other order information and build the XML format.
    $xmlRequest = new DOMDocument('1.0','UTF-8');

    $xmlRequest->formatOutput = true;
    $xmlSale = $xmlRequest->createElement('sale');

    // Amount, authentication, and Redirect-URL are typically the bare mininum.
    appendXmlNode($xmlSale,'api-key',$APIKey);
    appendXmlNode($xmlSale,'redirect-url',ssurl.site_root."/members/payments_nmi.php");
    appendXmlNode($xmlSale, 'amount', float_opt($product_total,2));
    appendXmlNode($xmlSale, 'ip-address', $_SERVER["REMOTE_ADDR"]);
    //appendXmlNode($xmlSale, 'processor-id' , 'processor-a');
    appendXmlNode($xmlSale, 'currency',$currency_code1);
    appendXmlNode($xmlSale, 'dup-seconds' , '2');

    // Some additonal fields may have been previously decided by user
    appendXmlNode($xmlSale, 'order-id', $product_type."-".$product_id);
    appendXmlNode($xmlSale, 'order-description', $product_name);
    appendXmlNode($xmlSale, 'tax-amount' , $order_info["product_tax"]);
    appendXmlNode($xmlSale, 'shipping-amount' , $order_info["product_shipping"]);

    /*if(!empty($_POST['customer-vault-id'])) {
        appendXmlNode($xmlSale, 'customer-vault-id' , $_POST['customer-vault-id']);
    }else {
         $xmlAdd = $xmlRequest->createElement('add-customer');
         appendXmlNode($xmlAdd, 'customer-vault-id' ,411);
         $xmlSale->appendChild($xmlAdd);
    }*/


    // Set the Billing and Shipping from what was collected on initial shopping cart form
    $xmlBillingAddress = $xmlRequest->createElement('billing');
    appendXmlNode($xmlBillingAddress,'first-name', $buyer_info["name"]);
    appendXmlNode($xmlBillingAddress,'last-name', $buyer_info["lastname"]);
    appendXmlNode($xmlBillingAddress,'address1', $buyer_info["billing_address"]);
    appendXmlNode($xmlBillingAddress,'city', $buyer_info["billing_city"]);
    appendXmlNode($xmlBillingAddress,'state', $buyer_info["billing_state"]);
    appendXmlNode($xmlBillingAddress,'postal', $buyer_info["billing_zipcode"]);
    //billing-address-email
    appendXmlNode($xmlBillingAddress,'country', $buyer_info["billing_country"]);
    appendXmlNode($xmlBillingAddress,'email', $buyer_info["email"]);

    appendXmlNode($xmlBillingAddress,'phone', $buyer_info["telephone"]);
    appendXmlNode($xmlBillingAddress,'company', $buyer_info["company"]);
    appendXmlNode($xmlBillingAddress,'address2', '');
    appendXmlNode($xmlBillingAddress,'fax', '');
    $xmlSale->appendChild($xmlBillingAddress);


    $xmlShippingAddress = $xmlRequest->createElement('shipping');
    appendXmlNode($xmlShippingAddress,'first-name', $buyer_info["name"]);
    appendXmlNode($xmlShippingAddress,'last-name', $buyer_info["lastname"]);
    appendXmlNode($xmlShippingAddress,'address1', $buyer_info["shipping_address"]);
    appendXmlNode($xmlShippingAddress,'city', $buyer_info["shipping_city"]);
    appendXmlNode($xmlShippingAddress,'state', $buyer_info["shipping_state"]);
    appendXmlNode($xmlShippingAddress,'postal', $buyer_info["shipping_zipcode"]);
    appendXmlNode($xmlShippingAddress,'country', $buyer_info["shipping_country"]);
    appendXmlNode($xmlShippingAddress,'phone', $buyer_info["telephone"]);
    appendXmlNode($xmlShippingAddress,'company', $buyer_info["company"]);
    appendXmlNode($xmlShippingAddress,'address2', '');
    $xmlSale->appendChild($xmlShippingAddress);


    // Products already chosen by user
    /*
    $xmlProduct = $xmlRequest->createElement('product');
    appendXmlNode($xmlProduct,'product-code' , 'SKU-123456');
    appendXmlNode($xmlProduct,'description' , 'test product description');
    appendXmlNode($xmlProduct,'commodity-code' , 'abc');
    appendXmlNode($xmlProduct,'unit-of-measure' , 'lbs');
    appendXmlNode($xmlProduct,'unit-cost' , '5.00');
    appendXmlNode($xmlProduct,'quantity' , '1');
    appendXmlNode($xmlProduct,'total-amount' , '7.00');
    appendXmlNode($xmlProduct,'tax-amount' , '2.00');

    appendXmlNode($xmlProduct,'tax-rate' , '1.00');
    appendXmlNode($xmlProduct,'discount-amount', '2.00');
    appendXmlNode($xmlProduct,'discount-rate' , '1.00');
    appendXmlNode($xmlProduct,'tax-type' , 'sales');
    appendXmlNode($xmlProduct,'alternate-tax-id' , '12345');

    $xmlSale->appendChild($xmlProduct);
*/


    $xmlRequest->appendChild($xmlSale);

    // Process Step One: Submit all transaction details to the Payment Gateway except the customer's sensitive payment information.
    // The Payment Gateway will return a variable form-url.
    $data = sendXMLviaCurl($xmlRequest,$gatewayURL);

    // Parse Step One's XML response
    $gwResponse = @new SimpleXMLElement($data);
    if ((string)$gwResponse->result ==1 ) {
        // The form url for used in Step Two below
        $formURL = $gwResponse->{'form-url'};
    } else {
        throw New Exception(print " Error, received " . $data);
    }



?>


<form action="<?=$formURL?>" method="POST" name="orderform" onsubmit="return check();">




<div style="margin-bottom:12px">
<div><b>Credit card number:</b></div>
<div><INPUT type ="text" class="ibox form-control" name="billing-cc-number" value=""></div>
</div>

<div style="margin-bottom:12px">
<div><b>Credit card expiration date (Format: mmYY. Example:1012):</b></div>
<div>
<INPUT type ="text" class="ibox form-control" name="billing-cc-exp" value="" style="width:100px">
</div>
</div>

<div style="margin-bottom:12px">
<div><b>CVV code:</b></div>
<div><INPUT type ="text" class="ibox form-control" name="cvv" style="width:100px"></div>
</div>

<input type="submit" class="isubmit" value="<?=word_lang("Pay Now")?>">

</form>

<?
}
else
{



    // Step Three: Once the browser has been redirected, we can obtain the token-id and complete
    // the transaction through another XML HTTPS POST including the token-id which abstracts the
    // sensitive payment information that was previously collected by the Payment Gateway.
    $tokenId = $_GET['token-id'];
    $xmlRequest = new DOMDocument('1.0','UTF-8');
    $xmlRequest->formatOutput = true;
    $xmlCompleteTransaction = $xmlRequest->createElement('complete-action');
    appendXmlNode($xmlCompleteTransaction,'api-key',$APIKey);
    appendXmlNode($xmlCompleteTransaction,'token-id',$tokenId);
    $xmlRequest->appendChild($xmlCompleteTransaction);


    // Process Step Three
    $data = sendXMLviaCurl($xmlRequest,$gatewayURL);


    $gwResponse = @new SimpleXMLElement((string)$data);


    if ((string)$gwResponse->result == 1 ) {
        print " <h2> Transaction was Approved, XML response was:</h2>\n";
       // print '<pre>' . (htmlentities($data)) . '</pre>';
       
       	$mass=explode("-",result($gwResponse->order-id));
    	$product_type=$mass[0];
    	$id=(int)$mass[1];
    	$transaction_id=transaction_add("nmi",result($gwResponse->transaction-id),result($product_type),$id);

		if($product_type=="credits")
		{
			credits_approve($id,$transaction_id);
			end_notification('credits_to_user',$id);
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
       

    } elseif((string)$gwResponse->result == 2)  {
        print " <h2> Transaction was Declined.</h2>\n";
        print " Decline Description : " . (string)$gwResponse->{'result-text'} ." </p>";
        //print " <p><h3>XML response was:</h3></p>\n";
        //print '<pre>' . (htmlentities($data)) . '</pre>';
    } else {
        print " <p><h2> Transaction caused an Error.</h2>\n";
        print " Error Description: " . (string)$gwResponse->{'result-text'} ." </p>";
       // print " <p><h3>XML response was:</h3></p>\n";
       // print '<pre>' . (htmlentities($data)) . '</pre>';
    }




}
?>


<?
//Functions for NMI gateway
  function sendXMLviaCurl($xmlRequest,$gatewayURL) {
   // helper function demonstrating how to send the xml with curl


    $ch = curl_init(); // Initialize curl handle
    curl_setopt($ch, CURLOPT_URL, $gatewayURL); // Set POST URL

    $headers = array();
    $headers[] = "Content-type: text/xml";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Add http headers to let it know we're sending XML
    $xmlString = $xmlRequest->saveXML();
    curl_setopt($ch, CURLOPT_FAILONERROR, 1); // Fail on errors
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Allow redirects
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return into a variable
    curl_setopt($ch, CURLOPT_PORT, 443); // Set the port number
    curl_setopt($ch, CURLOPT_TIMEOUT, 15); // Times out after 15s
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlString); // Add XML directly in POST

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);


    // This should be unset in production use. With it on, it forces the ssl cert to be valid
    // before sending info.
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    if (!($data = curl_exec($ch))) {
        print  "curl error =>" .curl_error($ch) ."\n";
        throw New Exception(" CURL ERROR :" . curl_error($ch));

    }
    curl_close($ch);

    return $data;
  }

  // Helper function to make building xml dom easier
  function appendXmlNode($parentNode,$name, $value) {
        $tempNode = new DOMElement($name,$value);
        $parentNode->appendChild($tempNode);
  }
 //End. Functions for NMI gateway
?>

<?include("../inc/footer.php");?>