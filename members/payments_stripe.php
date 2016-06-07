<?
$payment=preg_replace('/[^a-z0-9]/i',"",$_REQUEST["payment"]);
$site=$payment;
include("../admin/function/db.php");


if(!isset($_REQUEST["product_id"]) or !isset($_REQUEST["product_name"]) or !isset($_REQUEST["product_total"]) or !isset($_REQUEST["product_type"]))
{
	exit();
}


include("payments_settings.php");
?>
<?include("../inc/header.php");?>

<h1><?=word_lang("payment")?> - <?=$payments[$payment]?></h1>

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

<p>
<?
$product_id=(int)$_REQUEST["product_id"];
$product_name=result($_REQUEST["product_name"]);
$product_total=$_REQUEST["product_total"];
$product_type=result($_REQUEST["product_type"]);



//Check if Total is correct
if(!check_order_total($product_total,$product_type,$product_id))
{
	exit();
}



?>
<h2><?=word_lang("order")?>:</h2>

<?=show_order_content($product_type,$product_id)?>




<div class='login_header'><h2 style="margin-top:30px"><?=word_lang("credit card")?>:</h2></div>



<script type="text/javascript" src="https://js.stripe.com/v1/"></script>



 <script type="text/javascript">
// This identifies your website in the createToken call below
Stripe.setPublishableKey('<?=$site_stripe_account?>');
 
var stripeResponseHandler = function(status, response) {
var $form = $('#payment-form');
 
if (response.error) {
// Show the errors on the form
$form.find('.payment-errors').text(response.error.message);
$form.find('button').prop('disabled', false);
} else {
// token contains id, last4, and card type
var token = response.id;
// Insert the token into the form so it gets submitted to the server
$form.append($('<input type="hidden" name="stripeToken" />').val(token));
// and re-submit
$form.get(0).submit();
}
};
 
jQuery(function($) {
$('#payment-form').submit(function(e) {
var $form = $(this);
 
// Disable the submit button to prevent repeated clicks
$form.find('button').prop('disabled', true);
 
Stripe.createToken($form, stripeResponseHandler);
 
// Prevent the form from submitting with the default action
return false;
});
});
</script>

<form  id="payment-form" action="payments_stripe_go.php">
 <p class="payment-errors" style="color:red"></p>

<input type="hidden" name="product_id" value="<?=$product_id?>">
<input type="hidden" name="product_name" value="<?=$product_name?>">
<input type="hidden" name="product_total" value="<?=$product_total?>">
<input type="hidden" name="product_type" value="<?=$product_type?>">


<div class="form_field">
<span><b>Credit card number:</b></span>
<input type="text"  data-stripe="number"  size="20" class="ibox form-control">
</div>

<div class="form_field">
<span><b>Credit card expiration date:</b></span>
<input type="text"  data-stripe="exp-month" size="2" class="ibox form-control">
<input type="text" data-stripe="exp-year" size="4" class="ibox form-control">
</div>

<div class="form_field">
<span><b>CVV code:</b></span>
<input type="text" data-stripe="cvc" size="4" class="ibox form-control">
</div>


 <button type="submit" class="isubmit"><?=word_lang("Pay Now")?></button>

</form>



<?include("../inc/footer.php");?>