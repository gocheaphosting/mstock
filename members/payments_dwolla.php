<?if(!defined("site_root")){exit();}?>
<?
if($site_dwolla_account!="")
{

$apiKey = $site_dwolla_apikey;
$apiSecret = $site_dwolla_apisecret;
$token = '';
$timestamp 	= time();
$order_id 	= $product_type."-".$product_id;

$signature 	= hash_hmac('sha1', "{$apiKey}&{$timestamp}&{$order_id}",$apiSecret);

//Test
//$site_dwolla_account="812-713-9234";
?>

<form accept-charset="UTF-8" action="https://www.dwolla.com/payment/pay" 
method="post" name="process" id="process">

<input id="key" name="key" type="hidden" value="<?=$apiKey?>" />
<input id="signature" name="signature" type="hidden" value="<?=$signature?>" />
<input id="callback" name="callback" type="hidden" 
value="<?=surl.site_root."/members/payments_dwolla_go.php"?>" />
<input id="redirect" name="redirect" type="hidden" 
value="<?=surl.site_root."/members/payments_dwolla_go.php"?>" />
<input id="test" name="test" type="hidden" value="<?if($site_dwolla_test){echo("true");}else{echo("false");}?>" />
<input id="name" name="name" type="hidden" value="<?=$product_name?>" />
<input id="description" name="description" type="hidden" 
value="<?=$product_type?>" />
<input id="destinationid" name="destinationid" type="hidden" 
value="<?=$site_dwolla_account?>" />
<input id="amount" name="amount" type="hidden" value="<?=float_opt($product_total,2)?>" />
<input id="shipping" name="shipping" type="hidden" value="0.00" />
<input id="tax" name="tax" type="hidden" value="0.00" />
<input id="orderid" name="orderid" type="hidden" value="<?=$product_type?>-<?=$product_id?>" />
<input id="timestamp" name="timestamp" type="hidden" 
value="<?=$timestamp?>" />
</form>
<?
}
?>
