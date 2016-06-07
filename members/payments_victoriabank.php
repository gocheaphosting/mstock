<?if(!defined("site_root")){exit();}?>
<?
if($site_victoriabank_account=="" or $site_victoriabank_account2=="")
{
	exit();
}

function P_SIGN_ENCRYPT($OrderId, $Timestamp,$trtType,$Amount)
{
	$MAC  = '';
	$RSA_KeyPath = $_SERVER["DOCUMENT_ROOT"].site_root.'/admin/plugins/victoriabank/key.pem';
	$RSA_Key = file_get_contents ($RSA_KeyPath);
	$Data = array (
			'ORDER' => $OrderId,
			'NONCE' => '11111111000000011111',
			'TIMESTAMP' => $Timestamp,
			'TRTYPE' => $trtType,
			'AMOUNT' => $Amount
		);
		
	if (!$RSA_KeyResource = openssl_get_privatekey ($RSA_Key)) die ('Failed get private key');
	$RSA_KeyDetails = openssl_pkey_get_details ($RSA_KeyResource);
	$RSA_KeyLength = $RSA_KeyDetails['bits']/8;
	
	foreach ($Data as $Id => $Filed) $MAC .= strlen ($Filed).$Filed;
	
	$First = '0001';
	$Prefix = '003020300C06082A864886F70D020505000410';
	$MD5_Hash = md5 ($MAC); 
	$Data = $First;
	
	$paddingLength = $RSA_KeyLength - strlen ($MD5_Hash)/2 - strlen ($Prefix)/2 - strlen ($First)/2;
	for ($i = 0; $i < $paddingLength; $i++) $Data .= "FF";
	
	$Data .= $Prefix.$MD5_Hash;
	$BIN = pack ("H*", $Data);
	
	if (!openssl_private_encrypt ($BIN, $EncryptedBIN, $RSA_Key, OPENSSL_NO_PADDING)) 
	{
		while ($msg = openssl_error_string()) echo $msg . "<br />\n";
		die ('Failed encrypt');
	}
	
	$P_SIGN = bin2hex ($EncryptedBIN);
	
	return strtoupper ($P_SIGN);
}


$order_number=$product_id;
if($product_type=="order")
{
	$order_number="100".$product_id;
}
if($product_type=="credits")
{
	$order_number="101".$product_id;
}
if($product_type=="subscription")
{
	$order_number="102".$product_id;
}


$timestamp=date("Y").date("m").date("d").date("H").date("i").date("s");

?>
<form action="https://egateway.victoriabank.md/cgi-bin/cgi_link?" method="post" id="process" name="process">
<input type="hidden" value="<?=$product_total?>" name="AMOUNT" /><br />
<input type="hidden" value="<?=$currency_code1?>" name="CURRENCY" /><br />
<input type="hidden" value="<?=$order_number?>" name="ORDER" /><br />
<input type="hidden" value="<?=$product_name?>" name="DESC" /><br />
<input type="hidden" value="<?=$global_settings["site_name"]?>" name="MERCH_NAME" /><br />
<input type="hidden" value="<?=surl?>" name="MERCH_URL" /><br />
<input type="hidden" value="<?=$site_victoriabank_account2?>" name="MERCHANT" /><br />
<input type="hidden" value="<?=$site_victoriabank_account?>" name="TERMINAL" /><br />
<input type="hidden" value="<?=$_SESSION["people_email"]?>" name="EMAIL" /><br />
<input type="hidden" value="0" name="TRTYPE" /><br />
<input type="hidden" value="md" name="COUNTRY" /><br />
<input type="hidden" value="11111111000000011111" name="NONCE" /><br />
<input type="hidden" value="<?=surl.site_root."/members/payments_result.php?d=1"?>" name="BACKREF" /><br />
<input type="hidden" value="2" name="MERCH_GMT" /><br />
<input type="hidden" value="<?=$timestamp?>" name="TIMESTAMP" /><br />
<input type="hidden" value="<?=P_SIGN_ENCRYPT($order_number,$timestamp,0,$product_total)?>" name="P_SIGN" /><br />
<input type="hidden" value="en" name="LANG" /><br />
<input type="hidden" value="<?=$global_settings["company_address"]?>" name="MERCH_ADDRESS" /><br />
</form>