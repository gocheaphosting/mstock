<?if(!defined("site_root")){exit();}?>
<?
if($site_payumoney_account!="")
{

// Merchant key here as provided by Payu
$MERCHANT_KEY = $site_payumoney_account;

// Merchant Salt as provided by Payu
$SALT = $site_payumoney_password;

// End point - change to https://secure.payu.in for LIVE mode
if($site_payumoney_test)
{
	$PAYU_BASE_URL = "https://test.payu.in/_payment";
}
else
{
	$PAYU_BASE_URL = "https://secure.payu.in/_payment";
}

$txnid = $product_type."-".$product_id;


$productinfo_string='[{"name":"'.$product_name.'","description":"'.$product_type.'","value":"'.$product_id.'","isRequired":"false"}]';

$productinfo=json_encode(json_decode($productinfo_string));

$hash_string = $MERCHANT_KEY."|".$txnid."|".float_opt($product_total,2)."|".$productinfo."|".$_SESSION["people_name"]."|".$_SESSION["people_email"]."|||||||||||".$SALT;


$hash = strtolower(hash('sha512', $hash_string));
?>
<form action="<?=$PAYU_BASE_URL?>" method="post" name="process" id="process">
<input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
<input type="hidden" name="hash" value="<?php echo $hash ?>"/>
<input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
<input type="hidden" name="amount" value="<?=float_opt($product_total,2)?>">
<input type="hidden" name="firstname" value="<?=$_SESSION["people_name"]?>" />
<input type="hidden" name="email" value="<?=$_SESSION["people_email"]?>" />
<input type="hidden" name="phone" value="<?=$_SESSION["people_telephone"]?>" />
<input type="hidden" name="productinfo" value='<?=$productinfo?>' />
<input type="hidden" name="surl" value="<?=surl.site_root."/members/payments_payumoney_go.php"?>"/>
<input type="hidden" name="furl" value="<?=surl.site_root."/members/payments_result.php?d=2"?>"/>
<input type="hidden" name="service_provider" value='payu_paisa' />
</form> 



<?
}
?>