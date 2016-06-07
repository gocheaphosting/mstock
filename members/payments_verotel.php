<?if(!defined("site_root")){exit();}?>
<?
if($site_verotel_account!="")
{

include("../admin/plugins/verotel/FlexPay.php");

$purchase_url="";


$params = array(
	'shopID' => $site_verotel_account,
	'priceAmount' => float_opt($product_total,2),
	'priceCurrency' => $currency_code1,
	'referenceID' => $product_type . "-" . $product_id,
	'description' => $product_name,
	'email' =>  $_SESSION["people_email"],
	'version' => '3',
	'type' => 'purchase'
);

$purchase_url = FlexPay::get_purchase_URL($site_verotel_password, $params);

?>
<form action="<?=$purchase_url?>" method="post" name="process" id="process">
</form> 



<?
}
?>