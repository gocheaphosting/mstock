<?if(!defined("site_root")){exit();}?>
<?
if($site_paxum_account!="")
{
	?>
		<form action="https://www.paxum.com/payment/phrame.php?action=displayProcessPaymentLogin" method="post"  name="process" id="process">
			<input type="hidden" name="business_email" value="<?=$site_paxum_account?>" />
			<input type="hidden" name="button_type_id" value="1" />
			<input type="hidden" name="item_id" value="<?=$product_type?>-<?=$product_id?>" />
			<input type="hidden" name="item_name" value="<?=$product_name?>" />
			<input type="hidden" name="amount" value="<?=float_opt($product_total,2)?>" />
			<input type="hidden" name="currency" value="<?=$currency_code1?>" />
			<input type="hidden" name="ask_shipping" value="1" />
			<input type="hidden" name="cancel_url" value="<?=surl.site_root."/members/payments_result.php?d=2"?>" />
			<input type="hidden" name="finish_url" value="<?=surl.site_root."/members/payments_result.php?d=1"?>" />
			<input type="hidden" name="variables" value="notify_url=<?=surl.site_root."/members/payments_paxum_go.php"?>" />
		</form>
	<?
}
?>