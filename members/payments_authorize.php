<?if(!defined("site_root")){exit();}?>
<?
if($site_authorize_account!="")
{
	$loginID		= $site_authorize_account;
	$transactionKey = $site_authorize_account2;
	$amount 		= $product_total;
	$description 	= $product_type;
	$testMode		= "false";
	$invoice	= $product_id;
	$sequence	= rand(1, 1000);
	$timeStamp	= time();
	
	if( phpversion() >= '5.1.2' )
	{ 
		$fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey); 
	}
	else 
	{ 
		$fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey)); 
	}

?>
<form action="<?=authorize_url?>" method="POST" name="process" id="process">
	<input type='hidden' name='x_login' value='<?php echo $loginID; ?>' />
	<input type='hidden' name='x_amount' value='<?php echo $amount; ?>' />
	<input type='hidden' name='x_description' value='<?php echo $description; ?>' />
	<input type='hidden' name='x_invoice_num' value='<?php echo $invoice; ?>' />
	<input type='hidden' name='x_fp_sequence' value='<?php echo $sequence; ?>' />
	<input type='hidden' name='x_fp_timestamp' value='<?php echo $timeStamp; ?>' />
	<input type='hidden' name='x_fp_hash' value='<?php echo $fingerprint; ?>' />
	<input type='hidden' name='x_test_request' value='<?php echo $testMode; ?>' />
	<?if($site_authorize_ipn==true){?>
		<input type="hidden" name="x_relay_response" value="TRUE"/>
		<input type="hidden" name="x_relay_url" value="<?=surl.site_root."/members/payments_authorize_go.php"?>"/>
	<?}?>
	<input type='hidden' name='x_show_form' value='PAYMENT_FORM' />
</form>
<?
}
?>