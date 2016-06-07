<?
include("../admin/function/db.php");
include("payments_settings.php");




if($_POST['x_response_code'] == 1)
{
	$md5 = md5($site_authorize_account2 . $site_authorize_account . 
    $_POST["x_trans_id"] . $_POST['x_amount']);
	if (strtoupper($md5) != $_POST['x_MD5_Hash'])
	{
		exit();
	}	

	$transaction_id=transaction_add("authorize",$_POST["x_trans_id"],"credits",$_POST["x_invoice_num"]);

	if($_POST["x_description"]=="credits")
	{
		send_notification('credits_to_user',$_POST["x_invoice_num"]);
		send_notification('credits_to_admin',$_POST["x_invoice_num"]);
		credits_approve($_POST["x_invoice_num"],$transaction_id);
	}
	
	if($_POST["x_description"]=="subscription")
	{
		subscription_approve($_POST["x_invoice_num"]);
		send_notification('subscription_to_user',$_POST["x_invoice_num"]);
		send_notification('subscription_to_admin',$_POST["x_invoice_num"]);
	}

	if($_POST["x_description"]=="order")
	{
		order_approve($_POST["x_invoice_num"]);
		commission_add($_POST["x_invoice_num"]);
		coupons_add(order_user($_POST["x_invoice_num"]));
		send_notification('neworder_to_user',$_POST["x_invoice_num"]);
		send_notification('neworder_to_admin',$_POST["x_invoice_num"]);
	}
}

$db->close();
?>