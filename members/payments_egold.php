<?if(!defined("site_root")){exit();}?>
<?
if($site_egold_account!="" and $currency_egold!=0){



if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
header("payments_result.php?d=1");

}
else
{
?>

<form method="post" action="<?=egold_url?>"  name="process" id="process">
	<input type="hidden" name="PAYEE_ACCOUNT" value="<?=$site_egold_account?>" />
	<input type="hidden" name="PAYEE_NAME" value="<?=$site_egold_name?>" />
	<input type="hidden" name="PAYMENT_UNITS" value="<?=$currency_egold?>" />
	<input type="hidden" name="PAYMENT_METAL_ID" value="0" />
	<input type="hidden" name="PAYMENT_URL" value="<?=surl.site_root."/members/payments_process.php?mode=notification&product_type=".$product_type?>&processor=egold" />
	<input type="hidden" name="PAYMENT_URL_METHOD" value="POST" />
	<input type="hidden" name="STATUS_URL" value="<?=surl?><?=site_root?>/members/payments_egold.php" />
	<input type="hidden" name="NOPAYMENT_URL" value="<?=surl?><?=site_root?>/members/payments_result.php?d=2" />
	<input type="hidden" name="NOPAYMENT_URL_METHOD" value="POST" />
	<input type="hidden" name="PAYMENT_AMOUNT" value="<?=$product_total?>" />
	<input type="hidden" name="BAGGAGE_FIELDS" value="ORDER_NUM">
	<input type="hidden" name="ORDER_NUM" value="<?=$product_id?>">
</form>

<?
}
}
?>
