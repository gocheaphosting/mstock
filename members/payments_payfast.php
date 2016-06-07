<?if(!defined("site_root")){exit();}?>
<?
if($site_payfast_account!="")
{
	?>
		
		<form action="https://www.payfast.co.za/eng/process" method="post"  name="process" id="process">

			<!-- Receiver Details -->
			<input type="hidden" name="merchant_id" value="<?=$site_payfast_account?>">
			<input type="hidden" name="merchant_key" value="<?=$site_payfast_password?>">
			<input type="hidden" name="return_url" value="<?=surl.site_root."/members/payments_result.php?d=1"?>">
			<input type="hidden" name="cancel_url" value="<?=surl.site_root."/members/payments_result.php?d=2"?>">
			<input type="hidden" name="notify_url" value="<?=surl.site_root."/members/payments_payfast_go.php"?>">

			<!-- Payer Details -->
			<input type="hidden" name="name_first" value="<?=$_SESSION["people_name"]?>">
			<input type="hidden" name="email_address" value="<?=$_SESSION["people_email"]?>">

			<!-- Transaction Details -->
			<input type="hidden" name="m_payment_id" value="<?=$product_type?>-<?=$product_id?>">
			<input type="hidden" name="amount" value="<?=float_opt($product_total,2)?>">
			<input type="hidden" name="item_name" value="<?=$product_name?>">



			
			<?
			$security_vars=urlencode("merchant_id=".$site_payfast_account."&merchant_key=".$site_payfast_password."&return_url=".surl.site_root."/members/payments_result.php?d=1"."&cancel_url=".surl.site_root."/members/payments_result.php?d=2"."&notify_url=".surl.site_root."/members/payments_payfast_go.php"."&name_first=".$_SESSION["people_name"]."&m_payment_id=".$product_type."-".$product_id."&amount=".float_opt($product_total,2)."&item_name=".$product_name);
			?>

			<!-- Security -->
			<input type="hidden" name="signature" value="">

		</form>
	<?
}
?>