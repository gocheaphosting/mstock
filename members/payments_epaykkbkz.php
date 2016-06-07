<?if(!defined("site_root")){exit();}?>
<?
require_once("../admin/plugins/epaykkbkz/kkb.utils.php");
$self = $_SERVER['PHP_SELF'];
$path1 = '../admin/plugins/epaykkbkz/config.txt';	// Путь к файлу настроек config.dat
$order_id = $product_id;				// Порядковый номер заказа - преобразуется в формат "000001"

// Шифр валюты  - 840-USD, 398-Tenge
if($currency_code1=="KZT")
{
	$currency_id = "398";
}
elseif($currency_code1=="USD")
{
	$currency_id = "840";
}
else
{
	$currency_id = "398";
}



$amount = $product_total;				// Сумма платежа
$content = process_request($order_id,$currency_id,$amount,$path1); // Возвращает подписанный и base64 кодированный XML документ для отправки в банк
?>


   
   <?if($_SESSION["people_email"]!=""){?>
   		<form name="process" id="process"  method="post" action="https://epay.kkb.kz/jsp/process/logon.jsp">
   		<input type="hidden" name="email" size=50 maxlength=50  value="<?=$_SESSION["people_email"]?>">
   <?}else{?>
   		<form  method="post" action="https://epay.kkb.kz/jsp/process/logon.jsp">
   		Email: <input type="text" name="email" size=50 maxlength=50  value=""><br>
   		<input type="submit"  value="<?=word_lang("pay now")?>" >
   <?}?>
   <input type="hidden" name="Signed_Order_B64" value="<?php echo $content;?>">
   <input type="hidden" name="Language" value="eng">
   <input type="hidden" name="BackLink" value="<?=surl.site_root."/members/payments_result.php?d=1"?>">
   <input type="hidden" name="FailureBackLink" value="<?=surl.site_root."/members/payments_result.php?d=2"?>">
   <input type="hidden" name="PostLink" value="<?=surl.site_root."/members/payments_epaykkbkz_go.php"?>">
</form>

