<?
include("../admin/function/db.php");
include("payments_settings.php");


if($site_webmoney_account!="" and isset($_POST))
{

	//$token=$_POST["LMI_PAYEE_PURSE"].$_POST["LMI_PAYMENT_AMOUNT"].$_POST["LMI_PAYMENT_NO"].$_POST["LMI_MODE"].$_POST["LMI_SYS_INVS_NO"].$_POST["LMI_SYS_TRANS_NO"].$_POST["LMI_SYS_TRANS_DATE"].$_POST["LMI_SECRET_KEY"].$_POST["LMI_PAYER_PURSE"].$_POST["LMI_PAYER_WM"];
	
	$token=$_POST["LMI_PAYEE_PURSE"].$_POST["LMI_PAYMENT_AMOUNT"].$_POST["LMI_PAYMENT_NO"].$_POST["LMI_MODE"].$_POST["LMI_SYS_INVS_NO"].$_POST["LMI_SYS_TRANS_NO"].$_POST["LMI_SYS_TRANS_DATE"].$site_webmoney_key.$_POST["LMI_PAYER_PURSE"].$_POST["LMI_PAYER_WM"];
	
	//$token=md5($token);
	$token=strtoupper(hash('sha256',$token));

	if($site_webmoney_ipn==true and ($site_webmoney_key==$_POST["LMI_SECRET_KEY"] or $_POST["LMI_HASH"]==$token) and $_POST["LMI_PAYEE_PURSE"]==$site_webmoney_account and $_POST["LMI_MODE"]==0)
	{
		$transaction_id=transaction_add("webmoney",$_POST["LMI_SYS_TRANS_NO"],$_POST["ptype"],$_POST["LMI_PAYMENT_NO"]);

		if($_POST["ptype"]=="credits")
		{
			credits_approve($_POST["LMI_PAYMENT_NO"],$transaction_id);
			send_notification('credits_to_user',$_POST["LMI_PAYMENT_NO"]);
			send_notification('credits_to_admin',$_POST["LMI_PAYMENT_NO"]);
		}

		if($_POST["ptype"]=="subscription")
		{
			subscription_approve($_POST["LMI_PAYMENT_NO"]);
			send_notification('subscription_to_user',$_POST["LMI_PAYMENT_NO"]);
			send_notification('subscription_to_admin',$_POST["LMI_PAYMENT_NO"]);
		}

		if($_POST["ptype"]=="order")
		{
			order_approve($_POST["LMI_PAYMENT_NO"]);
			commission_add($_POST["LMI_PAYMENT_NO"]);
			coupons_add(order_user($_POST["LMI_PAYMENT_NO"]));
			send_notification('neworder_to_user',$_POST["LMI_PAYMENT_NO"]);
			send_notification('neworder_to_admin',$_POST["LMI_PAYMENT_NO"]);
		}
	}
}

$db->close();
?>