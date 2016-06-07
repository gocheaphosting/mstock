<?if(!defined("site_root")){exit();}?>
<?
if($site_alertpay_account!=""){




if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
if($site_alertpay_ipn==true)
{



if ($site_alertpay_security==$_POST["ap_securitycode"] and $_POST["ap_status"]=="Success")
{

	$transaction_id=transaction_add("alertpay",$_POST["ap_referencenumber"],$_POST["ap_description"],$_POST["ap_itemcode"]);

	if($_POST["ap_description"]=="credits")
	{
		credits_approve($_POST["ap_itemcode"],$transaction_id);
		send_notification('credits_to_user',$_POST["ap_itemcode"]);
		send_notification('credits_to_admin',$_POST["ap_itemcode"]);
	}

	if($_POST["ap_description"]=="subscription")
	{
		subscription_approve($_POST["ap_itemcode"]);
		send_notification('subscription_to_user',$_POST["ap_itemcode"]);
		send_notification('subscription_to_admin',$_POST["ap_itemcode"]);
	}

	if($_POST["ap_description"]=="order")
	{
		order_approve($_POST["ap_itemcode"]);
		commission_add($_POST["ap_itemcode"]);

		coupons_add(order_user($_POST["ap_itemcode"]));
		send_notification('neworder_to_user',$_POST["ap_itemcode"]);
		send_notification('neworder_to_admin',$_POST["ap_itemcode"]);
	}
}








}
}
else
{
?>




<form method="post" name="process" id="process" action="https://secure.payza.com/checkout">
<input type="hidden" name="ap_purchasetype" value="item"/>
<input type="hidden" name="ap_merchant" value="<?=$site_alertpay_account?>"/>
<input type="hidden" name="ap_itemname" value="<?=$product_name?>"/>
<input type="hidden" name="ap_currency" value="<?=$currency_code1?>"/>
<input type="hidden" name="ap_returnurl" value="<?=surl.site_root."/members/payments_result.php?d=1"?>"/>
<input type="hidden" name="ap_itemcode" value="<?=$product_id?>"/>
<input type="hidden" name="ap_quantity" value="1"/>
<input type="hidden" name="ap_description" value="<?=$product_type?>"/>
<input type="hidden" name="ap_amount" value="<?=$product_total?>"/>
<input type="hidden" name="ap_cancelurl" value="<?=surl.site_root."/members/payments_result.php?d=2"?>"/>
</form>





<?

}



}
?>