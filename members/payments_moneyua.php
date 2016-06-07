<?if(!defined("site_root")){exit();}?>
<?
if($site_moneyua_account!="")
{
	$strxml="<?xml version=\"1.0\" encoding=\"UTF-8\"?>
				<MAIN>
				<PAYMENT_AMOUNT>".($product_total*100)."</PAYMENT_AMOUNT>
				<PAYMENT_INFO>".$product_name."</PAYMENT_INFO>
				<PAYMENT_DELIVER></PAYMENT_DELIVER>
				<PAYMENT_ADDVALUE></PAYMENT_ADDVALUE>
				<PAYMENT_ORDER>".$product_type."-".$product_id."</PAYMENT_ORDER>
				<PAYMENT_TYPE>".result($_REQUEST["moneyua_method"])."</PAYMENT_TYPE>
				<PAYMENT_RULE>".($site_moneyua_commission+1)."</PAYMENT_RULE>
				<PAYMENT_VISA></PAYMENT_VISA>
				<PAYMENT_RETURNRES>".surl.site_root."/members/payments_moneyua_go.php</PAYMENT_RETURNRES>
				<PAYMENT_RETURN>".surl.site_root."/members/payments_result.php?d=1</PAYMENT_RETURN>
				<PAYMENT_RETURNMET>2</PAYMENT_RETURNMET>
				<PAYMENT_RETURNFAIL>".surl.site_root."/members/payments_result.php?d=2</PAYMENT_RETURNFAIL>
				<PAYMENT_TESTMODE>".$site_moneyua_test."</PAYMENT_TESTMODE>
				<PAYMENT_CODING>1</PAYMENT_CODING>
				</MAIN>";
				
	$strxml= base64_encode(rawurlencode($strxml));
	
	$hash  = md5($strxml.$site_moneyua_password);
	
	
	?>
		<form action="http://money.ua/sale.php" name="process" id="process" method="post">
			<input type="hidden" name="flagxml" value="1">
			<input type="hidden" name="strxml" value="<?=$strxml?>">
			<input type="hidden" name="MERCHANT_INFO" value="<?=$site_moneyua_account?>">
			<input type="hidden" name="PAYMENT_HASH" value="<?=$hash?>">
			<input type="hidden" name="coding" value="1">
		</form>
	<?
}
?>