<?
if(!defined("site_root")){exit();}


include($_SERVER["DOCUMENT_ROOT"].site_root."/members/payments_settings.php");

$payout_accounts["paypal"]="";
$payout_accounts["moneybookers"]="";
$payout_accounts["dwolla"]="";
$payout_accounts["qiwi"]="";
$payout_accounts["webmoney"]="";
$payout_accounts["bank"]="";
$product_total=float_opt($_POST["total"],2);


$product_id=0;
if($product_type="payout_seller")
{
	$sql="select id from commission where user=".(int)$_POST["user"]." and total<0 and gateway='".result($_POST["method"])."' order by data desc";
	$dr->open($sql);
	if(!$dr->eof)
	{
		$product_id=$dr->row["id"];
	}
}

if($product_type="payout_affiliate")
{
	$sql="select data,aff_referal from affiliates_signups where aff_referal=".(int)$_POST["user"]." and total<0 and gateway='".result($_POST["method"])."' order by data desc";
	$dr->open($sql);
	if(!$dr->eof)
	{
		$product_id=$dr->row["data"]."-".$dr->row["aff_referal"];
	}
}


$sql="select id_parent,login,paypal,moneybookers,dwolla,qiwi,webmoney,bank_name,bank_account,payson from users where id_parent=".(int)$_POST["user"];
$dr->open($sql);
if(!$dr->eof)
{
	$payout_accounts["paypal"]=$dr->row["paypal"];
	$payout_accounts["moneybookers"]=$dr->row["moneybookers"];
	$payout_accounts["dwolla"]=$dr->row["dwolla"];
	$payout_accounts["qiwi"]=$dr->row["qiwi"];
	$payout_accounts["webmoney"]=$dr->row["webmoney"];
	$payout_accounts["payson"]=$dr->row["payson"];
	$product_name=$global_settings["site_name"].". Payout to ".$dr->row["login"];
}

$page_header="<html>
<body onLoad='document.process.submit()' bgcolor='#525151'>
<div style='margin:250px auto 0px auto;width:100px;background-color:#373737;border: #4a4a4a 4px solid;padding:20px;font: 15pt Arial;color:#ffffff'>".word_lang("loading")."...<div><center><img src='".site_root."/images/upload_loading.gif'></center></div></div>";

$page_footer="</body></html>";

if($_POST["method"]=="paypal")
{
	echo($page_header);
	?>
	<form method="post" name="process" id="process" action="https://www.paypal.com/cgi-bin/webscr">
		<input type="hidden" name="rm" value="2"/>
		<input type="hidden" name="cmd" value="_xclick"/>
		<input type="hidden" name="business" value="<?=$payout_accounts["paypal"]?>"/>
		<input type="hidden" name="item_name" value="<?=$product_name?>"/>
		<input type="hidden" name="item_number" value="<?=$product_id?>"/>
		<input type="hidden" name="amount" value="<?=$product_total?>"/>
		<input type="hidden" name="currency_code" value="<?=$currency_code1?>"/>
		<input type="hidden" name="notify_url" value="<?=surl.site_root."/members/payments_process.php?mode=notification&product_type=".$product_type?>&processor=paypal"/>
		<input type="hidden" name="return" value="<?=$link_back."&t=1&id=".$product_id?>"/>
		<input type="hidden" name="cancel_return" value="<?=$link_back."&t=2&id=".$product_id?>"/>
	</form>
	<?
	echo($page_footer);
}

if($_POST["method"]=="moneybookers")
{
	echo($page_header);
	?>
	<form method="post" action="https://www.moneybookers.com/app/payment.pl"  name="process" id="process">
        <input type="hidden" name="pay_to_email" value="<?=$payout_accounts["moneybookers"]?>" />
        <input type="hidden" name="language" value="EN" />
        <input type="hidden" name="amount" value="<?=$product_total?>" />
        <input type="hidden" name="currency" value="<?=$currency_code1?>" />
        <input type="hidden" name="detail1_description" value="<?=$product_name?>" />
        <input type="hidden" name="detail1_text" value="<?=$product_id?>" />
        <input type="hidden" name="transaction_id" value="<?=$product_id?>" />
        <input type="hidden" name="return_url" value="<?=$link_back."&t=1&id=".$product_id?>" />
        <input type="hidden" name="cancel_url" value="<?=$link_back."&t=2&id=".$product_id?>" />
    </form>
	<?
	echo($page_footer);
}

if($_POST["method"]=="dwolla" and $payout_accounts["dwolla"]!="" and $site_dwolla_apikey!="")
{
	include("../plugins/dwolla/dwolla.php");
	$apiKey = $site_dwolla_apikey;
	$apiSecret = $site_dwolla_apisecret;
	$token = '';
	$pin = $site_dwolla_pin;

	
	$Dwolla = new DwollaRestClient();
	$Dwolla->setToken($token);
	$transactionId = $Dwolla->send($pin,$payout_accounts["dwolla"],$product_total,'',$product_name);
	if(!$transactionId) { echo "Error: {$Dwolla->getError()} \n"; } // Check for errors
	else { echo "Send transaction ID: {$transactionId} \n"; } // Print Transaction ID
}

if($_POST["method"]=="qiwi")
{
	ob_start();
	ob_clean();
	header("location:https://w.qiwi.com/payment/transfer/form.action");
	exit();
}

if($_POST["method"]=="webmoney")
{
	echo($page_header);
	?>
	<form method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp" name="process" id="process">
		<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?=float_opt($product_total,2)?>">
		<input type="hidden" name="LMI_PAYMENT_DESC" value="<?=$product_name?>">
		<input type="hidden" name="LMI_PAYMENT_NO" value="<?=$product_id?>">
		<input type="hidden" name="LMI_PAYEE_PURSE" value="<?=$payout_accounts["webmoney"]?>">
		<input type="hidden" name="ptype" value="<?=$product_type?>">
		<input type="hidden" name="LMI_SUCCESS_URL" value="<?=$link_back."&t=1&id=".$product_id?>" />
       	<input type="hidden" name="LMI_FAIL_URL" value="<?=$link_back."&t=2&id=".$product_id?>" />
	</form>
	<?
	echo($page_footer);
}






if($_POST["method"]=="other" or $_POST["method"]=="bank" or $_POST["method"]=="payson")
{
	ob_start();
	ob_clean();
	header("location:".$link_back);
	exit();
}
?>
