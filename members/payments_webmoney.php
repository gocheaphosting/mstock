<?if(!defined("site_root")){exit();}?>
<?
if($site_webmoney_account!="")
{
?>

<form method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp" name="process" id="process">
	<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?=float_opt($product_total,2)?>">
	<input type="hidden" name="LMI_PAYMENT_DESC" value="<?=$product_name?>">
	<input type="hidden" name="LMI_PAYMENT_NO" value="<?=$product_id?>">
	<input type="hidden" name="LMI_PAYEE_PURSE" value="<?=$site_webmoney_account?>">
	<input type="hidden" name="ptype" value="<?=$product_type?>">
</form>

<?
}
?>