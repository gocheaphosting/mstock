<?if(!defined("site_root")){exit();}?>
<?
if($site_cashu_account!=""){

$token=md5($site_cashu_account.":".float_opt($product_total,2).":".strtolower($currency_code1).":".$site_cashu_key);



if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
if($site_cashu_ipn==true and $token==$_POST["token"])
{


	$transaction_id=transaction_add("cashu",$_POST["trn_id"],$_POST["txt1"],$_POST["txt2"]);

	if($_POST["txt1"]=="credits")
	{
					send_notification('credits_to_user',$_POST["x_invoice_num"]);
send_notification('credits_to_admin',$_POST["x_invoice_num"]);
		credits_approve($_POST["x_invoice_num"],$transaction_id);
	}

	if($_POST["txt1"]=="subscription")
	{
		subscription_approve($_POST["txt2"]);
				send_notification('subscription_to_user',$_POST["x_invoice_num"]);
send_notification('subscription_to_admin',$_POST["x_invoice_num"]);
	}

	if($_POST["txt1"]=="order")
	{
		order_approve($_POST["txt2"]);
		commission_add($_POST["txt2"]);

		coupons_add(order_user($_POST["txt2"]));
								send_notification('neworder_to_user',$_POST["txt2"]);
send_notification('neworder_to_admin',$_POST["txt2"]);
	}









}
}
else
{

?>





<form method="post" name="process" id="process" action="<?=cashu_url?>">
<input type="hidden" name="merchant_id" value="<?=$site_cashu_account?>">
<input type="hidden" name="token" value="<?=$token?>">
<input type="hidden" name="display_text" value="<?=$product_name?>">
<input type="hidden" name="currency" value="<?=$currency_code1?>">
<input type="hidden" name="amount" value="<?=float_opt($product_total,2)?>">
<input type="hidden" name="language" value="en">
<input type="hidden" name="email" value="<?=$_SESSION["people_email"]?>">
<input type="hidden" name="txt1" value="<?=$product_type?>">
<input type="hidden" name="txt2" value="<?=$product_id?>">
</form>







<?

}



}
?>