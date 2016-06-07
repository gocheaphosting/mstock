<?
include("../admin/function/db.php");
include("payments_settings.php");


if($site_myvirtualmerchant_account!="")
{


	if(isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"]=="on")
	{
		$url="https://www.myvirtualmerchant.com/VirtualMerchant/process.do";
	}
	else
	{
		$url="https://demo.myvirtualmerchant.com/VirtualMerchantDemo/process.do";
	}


	$fields = array(
	'ssl_merchant_id'=>$site_myvirtualmerchant_account,
	'ssl_user_id'=>$site_myvirtualmerchant_account2,
	'ssl_pin'=>$site_myvirtualmerchant_code,
	'ssl_show_form'=>'false',
	'ssl_result_format'=>'html',
	'ssl_test_mode'=>'false',
	'ssl_receipt_link_method'=>'REDG',
	'ssl_receipt_apprvl_method'=>'POST',
	'ssl_error_url'=>surl.site_root."/members/payments_result.php?d=2",
	'ssl_receipt_apprvl_get_url'=>surl.site_root."/members/payments_myvirtualmerchant_go.php",
	'ssl_transaction_type'=>'ccsale',
	'ssl_amount'=>urlencode(result($_POST["product_total"])),
	'ssl_card_number'=>urlencode(result($_POST["card_number"])),
	'ssl_exp_date'=>urlencode(result($_POST["card_month"]).substr(result($_POST["card_year"]),2,3)),
	'ssl_cvv2cvc2'=>urlencode(result($_POST["cvv"])),
	'ssl_invoice_number'=>urlencode(result($_POST["product_id"])."-".result($_POST["product_type"]))
	);
	

	$fields_string = '';
	foreach($fields as $key=>$value) { $fields_string .=$key.'='.$value.'&'; }
	rtrim($fields_string, "&");

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$result_text = curl_exec($ch);
	curl_close($ch);

	//echo "Processing, please wait...";


}
?>