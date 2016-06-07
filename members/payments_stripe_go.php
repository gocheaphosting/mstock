<?
$site="paypalpro";
include("../admin/function/db.php");
include("payments_settings.php");

if(!isset($_REQUEST["product_id"]) or !isset($_REQUEST["product_name"]) or !isset($_REQUEST["product_total"]) or !isset($_REQUEST["product_type"]))
{
	exit();
}

?>
<?include("../inc/header.php");?>

<h1><?=word_lang("payment")?> - Stripe</h1>

<?




$product_id=(int)$_REQUEST["product_id"];
$product_name=result($_REQUEST["product_name"]);
$product_total=$_REQUEST["product_total"];
$product_type=result($_REQUEST["product_type"]);





$buyer_info=array();
get_buyer_info($_SESSION["people_id"],$product_id,$product_type);

$order_info=array();
get_order_info($product_id,$product_type);

//Check if Total is correct
if(!check_order_total($product_total,$product_type,$product_id))
{
	//exit();
}



include("../admin/plugins/stripe/Stripe.php");
Stripe::setApiKey($site_stripe_password);

// Get the credit card details submitted by the form
$token = $_REQUEST['stripeToken'];


// Create the charge on Stripe's servers - this will charge the user's card
try {
$charge = Stripe_Charge::create(array(
  "amount" => $_REQUEST["product_total"]*100, // amount in cents, again
  "currency" => $currency_code1,
  "card" => $token,
  "description" => result($_REQUEST["product_name"]))
);



	$transaction_id=transaction_add("stripe","",$_REQUEST["product_type"],$_REQUEST["product_id"]);

	if($_REQUEST["product_type"]=="credits")
	{
		credits_approve($_REQUEST["product_id"],$transaction_id);
		send_notification('credits_to_user',$_REQUEST["product_id"]);
		send_notification('credits_to_admin',$_REQUEST["product_id"]);
	}

	if($_REQUEST["product_type"]=="subscription")
	{
		subscription_approve($_REQUEST["product_id"]);
		send_notification('subscription_to_user',$_REQUEST["product_id"]);
		send_notification('subscription_to_admin',$_REQUEST["product_id"]);
	}

	if($_REQUEST["product_type"]=="order")
	{
		order_approve($_REQUEST["product_id"]);
		commission_add($_REQUEST["product_id"]);

		coupons_add(order_user($_REQUEST["product_id"]));
		send_notification('neworder_to_user',$_REQUEST["product_id"]);
		send_notification('neworder_to_admin',$_REQUEST["product_id"]);
	}	
	
	echo("<p>Thank you! Your transaction has been sent successfully.</p>");

	
} catch(Stripe_CardError $e) {
  // The card has been declined
  echo("<p>The transaction has been declined.</p>");
}
?>


<?

	if(isset($_REQUEST["product_id"]) and isset($_REQUEST["product_type"]))
	{
		$_GET["product_id"]=$_REQUEST["product_id"];
		$_GET["product_type"]=$_REQUEST["product_type"];
		$_GET["print"]=1;
		include("payments_statement.php");
	}
?>





<?include("../inc/footer.php");?>