<?
include("../admin/function/db.php");
include("payments_settings.php");

if($site_victoriabank_account=="" or $site_victoriabank_account2=="")
{
	exit();
}



function P_SIGN_DECRYPT($P_SIGN, $ACTION, $RC, $RRN, $ORDER, $AMOUNT)
{
$InData = array (
			'ACTION' => $ACTION,
			'RC' => $RC,
			'RRN' => $RRN,
			'ORDER' => $ORDER ,
			'AMOUNT' => $AMOUNT
		);

foreach ($InData as $Id => $Filed) $MAC .= strlen ($Filed).$Filed;
$MD5_Hash_In = strtoupper (md5 ($MAC)); 


$P_SIGNBIN = hex2bin ($P_SIGN);


$RSA_KeyPath = $_SERVER["DOCUMENT_ROOT"].site_root.'/admin/plugins/victoriabank/victoria_pub.pem';
$RSA_Key = file_get_contents ($RSA_KeyPath);
if (!$RSA_KeyResource = openssl_get_publickey($RSA_Key)) die ('Failed get public key');

if (!openssl_public_decrypt ($P_SIGNBIN,$DECRYPTED_BIN,$RSA_Key))
{
		while ($msg = openssl_error_string()) echo $msg . "<br />\n";
		die ('Failed decrypt');
	}

$DECRYPTED = strtoupper( bin2hex ($DECRYPTED_BIN));
$Prefix = '3020300C06082A864886F70D020505000410';
$DECRYPTED_HASH=str_replace($Prefix,'',$DECRYPTED);

if ($DECRYPTED_HASH==$MD5_Hash_In) {
$RESULT="OK";
} else {
$RESULT="NOK";
}

return $RESULT;

}


if(P_SIGN_DECRYPT(@$_POST["P_SIGN"], @$_POST["ACTION"], @$_POST["RC"], @$_POST["RRN"], @$_POST["ORDER"], @$_POST["AMOUNT"])=="OK")
{  
    	if(substr($_POST["ORDER"],0,2)=="100")
    	{
    		$product_type="order";
    	}
    	if(substr($_POST["ORDER"],0,2)=="101")
    	{
    		$product_type="credits";
    	}
    	if(substr($_POST["ORDER"],0,2)=="102")
    	{
    		$product_type="subscription";
    	}
    	
    	$id=substr($_POST["ORDER"],3);
    	$id=(int)$id;
    		
    	$transaction_id=transaction_add("victoriabank",result($_POST["INT_REF"]),result($product_type),$id);

		if($product_type=="credits")
		{
			credits_approve($id,$transaction_id);
			send_notification('credits_to_user',$id);
			send_notification('credits_to_admin',$id);
		}

		if($product_type=="subscription")
		{
			subscription_approve($id);
			send_notification('subscription_to_user',$id);
			send_notification('subscription_to_admin',$id);
		}

		if($product_type=="order")
		{
			order_approve($id);
			commission_add($id);
			coupons_add(order_user($id));
			send_notification('neworder_to_user',$id);
			send_notification('neworder_to_admin',$id);
		}
}

$db->close();
?>