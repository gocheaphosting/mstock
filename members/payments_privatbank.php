<?
if(!defined("site_root")){exit();}

include("../admin/plugins/liqpay/LiqPay.php");

if($site_privatbank_account!="")
{
	$liqpay = new LiqPay($site_privatbank_account, $site_privatbank_password);
	$html = $liqpay->cnb_form(array(
	'action'         => 'pay',
	'version'         => 3,
	'amount'         => float_opt($product_total,2),
	'currency'       => $currency_code1,
	'description'    => $product_name,
	'order_id'       => $product_type."-".$product_id,
	'server_url'       => surl.site_root."/members/payments_privatbank_go.php",
	'result_url'       => surl.site_root."/members/payments_result.php?d=1"
	));
	
	echo($html);
}
?>