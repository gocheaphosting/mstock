<?if(!defined("site_root")){exit();}?>
<?
if($site_dotpay_account!="")
{
	?>
		<form action="https://ssl.dotpay.pl/" name="process" id="process" method="POST">
			<input type="hidden" name="id" value="<?=$site_dotpay_account?>">
			<input type="hidden" name="amount" value="<?=float_opt($product_total,2)?>">
			<input type="hidden" name="currency" value="<?=$currency_code1?>">
			<input type="hidden" name="description" value="<?=$product_type?>-<?=$product_id?>">
			<input type="hidden" name="lang" value="en">
			<input type="hidden" name="URL" value="<?=surl.site_root."/members/payments_result.php?d=1"?>">
			<input type="hidden" name="URLC" value="<?=surl.site_root."/members/payments_dotpay_go.php"?>">
		</form>
	<?
}
?>