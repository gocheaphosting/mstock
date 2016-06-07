<?if(!defined("site_root")){exit();}?>
<?
if($site_cashx_account!=""){




if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
if($site_cashx_ipn==true)
{

// Get the MD5 sum of your IPN security code
$md5_ipn_code = md5($site_cashx_security);

// Set some of the basic variables
$x_payer = $_POST['x_payer'];
$x_merchant = $_POST['x_merchant'];
$x_totalamount = $_POST['x_totalamount'];
$x_currency = $_POST['x_currency'];
$x_referenceid = $_POST['x_referenceid'];
$x_test = $_POST['x_test'];

// Build the plain text string
$myhash = strtoupper($x_payer) . ':' . strtoupper($x_merchant) . ':' . $x_totalamount . ':' . $x_currency . ':' . $x_referenceid . ':' . $x_test . ':' . $md5_ipn_code;

// MD5 Encode the hash
$myhash = md5($myhash);

// Compare the local hash to the passed hash, and make sure we are not in test mode
if($myhash == $_POST['x_hash'])
{
    $flag_response=true;
}
else
{
    $flag_response=false;
}


//$response = "VERIFIED";
if ($flag_response and $_POST["x_status"]=="SUCCESS")
{

	$transaction_id=transaction_add("cashx",$_POST["x_referenceid"],$_POST["x_description"],$_POST["x_itemcode"]);

	if($_POST["x_description"]=="credits")
	{
		credits_approve($_POST["x_itemcode"],$transaction_id);
		send_notification('credits_to_user',$_POST["x_itemcode"]);
		send_notification('credits_to_admin',$_POST["x_itemcode"]);
	}

	if($_POST["x_description"]=="subscription")
	{
		subscription_approve($_POST["x_itemcode"]);
		send_notification('subscription_to_user',$_POST["x_itemcode"]);
		send_notification('subscription_to_admin',$_POST["x_itemcode"]);
	}

	if($_POST["x_description"]=="order")
	{
		order_approve($_POST["x_itemcode"]);
		commission_add($_POST["x_itemcode"]);

		coupons_add(order_user($_POST["x_itemcode"]));
		send_notification('neworder_to_user',$_POST["x_itemcode"]);
		send_notification('neworder_to_admin',$_POST["x_itemcode"]);
	}
}








}
}
else
{
?>







<form method="post" name="process" id="process" action="<?=cashx_url?>">
<input type="hidden" name="payee_email" value="<?=$site_cashx_account?>">
<input type="hidden" name="purchase_type" value="goods">
<input type="hidden" name="item_name" value="<?=$product_name?>">
<input type="hidden" name="item_desc" value="<?=$product_type?>">
<input type="hidden" name="item_code" value="<?=$product_id?>">
<input type="hidden" name="currency" value="<?=$currency_code1?>">
<input type="hidden" name="amount" value="<?=$product_total?>">
<input type="hidden" name="return_url" value="<?=surl.site_root."/members/payments_result.php?d=1"?>">
<input type="hidden" name="cancel_url" value="<?=surl.site_root."/members/payments_result.php?d=2"?>">
</form>




<?

}



}
?>