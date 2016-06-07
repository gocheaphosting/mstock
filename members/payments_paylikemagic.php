<?if(!defined("site_root")){exit();}?>
<?
if($site_paylikemagic_account!="")
{
	?>
	
	
	
	
	<form action="https://api.paylikemagic.com/api/payment/create" method="post"  name="process" id="process">
		<input type="hidden" name="Plm_merchant_key" value="<?=$site_paylikemagic_account?>">		
		<input type="hidden" name="Plm_wallet_key" value="<?=$site_paylikemagic_password?>">	
		<input type="hidden" name="plm_memo" value="<?=$product_name?>">
		<input type="hidden" name="plm_notify_url" value="<?=surl.site_root."/members/payments_paylikemagic_go.php"?>">
		<input type="hidden" name="plm_payment_ref" value="<?=$product_type?>-<?=$product_id?>">
		<input type="hidden" name="plm_item_name_0" value="<?=$product_name?>">
		<input type="hidden" name="plm_item_amount_0" value="<?=float_opt($product_total,2)?>">
	</form>

	<?
}
?>