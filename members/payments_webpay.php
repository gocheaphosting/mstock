<?if(!defined("site_root")){exit();}?>
<?
if($site_webpay_account!="")
{	
	$wsb_seed = 1242649174;
	$wsb_storeid = $site_webpay_account;
	$wsb_order_num = $product_type."-".$product_id;
	$wsb_test = $site_webpay_test;
	$wsb_currency_id = "BYR";
	$wsb_total = $product_total;
	$SecretKey = $site_webpay_password;

	$wsb_signature = sha1($wsb_seed.$wsb_storeid.$wsb_order_num.$wsb_test.$wsb_currency_id.$wsb_total.$SecretKey); 
	
	if($site_webpay_test)
	{
		$url="https://secure.sandbox.webpay.by:8843";
	}
	else
	{
		$url="https://payment.webpay.by";	
	}
	?>		
		<form action="<?=$url?>" method="post"  name="process" id="process">
		<input type="hidden" name="*scart">
		<input type="hidden" name="wsb_version" value="2">
		<input type="hidden" name="wsb_language_id" value="russian">
		<input type="hidden" name="wsb_storeid" value="<?=$site_webpay_account?>" >
		<input type="hidden" name="wsb_store" value="<?=$global_settings["site_name"]?>" >
		<input type="hidden" name="wsb_order_num" value="<?=$product_type?>-<?=$product_id?>" >
		<input type="hidden" name="wsb_test" value="<?=$site_webpay_test?>" >
		<input type="hidden" name="wsb_currency_id" value="BYR" >
		<input type="hidden" name="wsb_seed" value="<?=$wsb_seed?>">
		<input type="hidden" name="wsb_return_url" value="<?=surl.site_root."/members/payments_webpay_go.php"?>">
		<input type="hidden" name="wsb_cancel_return_url" value="<?=surl.site_root."/members/payments_result.php?d=2"?>">
		<input type="hidden" name="wsb_notify_url" value="<?=surl.site_root."/members/payments_webpay_go.php"?>">
		<input type="hidden" name="wsb_email" value="<?=$_SESSION["people_email"]?>" >
		<input type="hidden" name="wsb_invoice_item_name[0]" value="<?=$product_name?>">
		<input type="hidden" name="wsb_invoice_item_quantity[0]" value="1">
		<input type="hidden" name="wsb_invoice_item_price[0]" value="<?=$product_total?>">
		<input type="hidden" name="wsb_total" value="<?=$product_total?>" >
		<input type="hidden" name="wsb_signature" value="<?=$wsb_signature?>" >
		</form>
	<?
}
?>