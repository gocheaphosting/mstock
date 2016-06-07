<?if(!defined("site_root")){exit();}?>
<?
if($site_payson_account=="")
{
	exit();
}

/*
 * Payson API Integration example for PHP
 *
 * More information can be found att https://api.payson.se
 *
 */

/*
 * On every page you need to use the API you
 * need to include the file lib/paysonapi.php
 * from where you installed it.
 */

require_once '../admin/plugins/payson/paysonapi.php';

/*
 * Account information. Below is all the variables needed to perform a purchase with
 * payson. Replace the placeholders with your actual information 
 */

// Your agent ID and md5 key
$agentID = $site_payson_account2;
$md5Key = $site_payson_password;

// URLs used by payson for redirection after a completed/canceled purchase.

$returnURL = surl.site_root."/members/payments_result.php?d=1";
$cancelURL = surl.site_root."/members/payments_result.php?d=2";

// Please note that only IP/URLS accessible from the internet will work
$ipnURL = surl.site_root."/members/payments_payson_go.php";

// Account details of the receiver of money
$receiverEmail = $site_payson_account;

// Amount to send to receiver
$amountToReceive = $product_total;

// Information about the sender of money
$senderEmail = $_SESSION["people_email"];
$sql="select name,lastname from users where id_parent=".(int)$_SESSION["people_id"];
$rs->open($sql);
if(!$rs->eof)
{
	$senderFirstname = $rs->row["name"];
	$senderLastname = $rs->row["lastname"];
}

/* Every interaction with Payson goes through the PaysonApi object which you set up as follows.  
 * For the use of our test or live environment use one following parameters:
 * TRUE: Use test environment, FALSE: use live environment */
$credentials = new PaysonCredentials($agentID, $md5Key);
$api = new PaysonApi($credentials, TRUE);

/*
 * To initiate a direct payment the steps are as follows
 *  1. Set up the details for the payment
 *  2. Initiate payment with Payson
 *  3. Verify that it suceeded
 *  4. Forward the user to Payson to complete the payment
 */

/*
 * Step 1: Set up details
 */


// Details about the receiver
$receiver = new Receiver(
        $receiverEmail, // The email of the account to receive the money
        $amountToReceive); // The amount you want to charge the user, here in SEK (the default currency)
$receivers = array($receiver);

// Details about the user that is the sender of the money
$sender = new Sender($senderEmail, $senderFirstname, $senderLastname);

$payData = new PayData($returnURL, $cancelURL, $ipnURL, $product_name, $sender, $receivers);

//Set the list of products. For direct payment this is optional
$orderItems = array();
$orderItems[] = new OrderItem($product_type."-".$product_id, $product_total, 1, 0,$product_id);

$payData->setOrderItems($orderItems);


//Set the payment method
//$constraints = array(FundingConstraint::BANK, FundingConstraint::CREDITCARD); // bank and card
//$constraints = array(FundingConstraint::INVOICE); // only invoice
$constraints = array(FundingConstraint::BANK, FundingConstraint::CREDITCARD, FundingConstraint::INVOICE); // bank, card and invoice
//$constraints = array(FundingConstraint::BANK); // only bank
$payData->setFundingConstraints($constraints);

//Set the payer of Payson fees
//Must be PRIMARYRECEIVER if using FundingConstraint::INVOICE
$payData->setFeesPayer(FeesPayer::PRIMARYRECEIVER);

// Set currency code
$payData->setCurrencyCode(CurrencyCode::SEK);

// Set locale code
$payData->setLocaleCode(LocaleCode::SWEDISH);

// Set guarantee options
$payData->setGuaranteeOffered(GuaranteeOffered::OPTIONAL);

/*
 * Step 2 initiate payment
 */
$payResponse = $api->pay($payData);

/*
 * Step 3: verify that it suceeded
 */

//var_dump($payResponse);
 
if ($payResponse->getResponseEnvelope()->wasSuccessful()) {
    /*
     * Step 4: forward user
     */
    //header("Location: " . $api->getForwardPayUrl($payResponse));
    $url=$api->getForwardPayUrl($payResponse);
}
else
{
	$url=$cancelURL;
}
    ?>
    <form action="<?=$url?>" method="post"  name="process" id="process">
    </form>