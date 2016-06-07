<?if(!defined("site_root")){exit();}?>
<?
if($site_2checkout_account!=""){


if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
if($site_2checkout_ipn==true)
{



if($_POST['credit_card_processed'] == 'Y')
{

	$transaction_id=transaction_add("2checkout",$_POST["order_number"],"credits",$_POST["cart_order_id"]);

	if($_POST["product_type"]=="credits")
	{
		credits_approve($_POST["cart_order_id"],$transaction_id);
				send_notification('credits_to_user',$_POST["cart_order_id"]);
send_notification('credits_to_admin',$_POST["cart_order_id"]);
	}
	if($_POST["product_type"]=="subscription")
	{
		subscription_approve($_POST["cart_order_id"]);
		send_notification('subscription_to_user',$_POST["cart_order_id"]);
send_notification('subscription_to_admin',$_POST["cart_order_id"]);
	}

	if($_POST["product_type"]=="order")
	{
		order_approve($_POST["cart_order_id"]);
		commission_add($_POST["cart_order_id"]);

		coupons_add(order_user($_POST["cart_order_id"]));
				send_notification('neworder_to_user',$_POST["cart_order_id"]);
send_notification('neworder_to_admin',$_POST["cart_order_id"]);
	}
header("location:payments_result.php?d=1");
}
elseif($_POST['credit_card_processed'] == 'K')
{
header("location:payments_result.php?d=3");
}
else
{
header("location:payments_result.php?d=2");
}








}
header("location:payments_result.php?d=1");
}
else
{
?>

<form action="<?=checkout2_url?>" method="POST"  name="process" id="process">
	<input type="hidden" name="sid" value="<?=$site_2checkout_account?>">
	<input type="hidden" name="total" value="<?=$product_total?>">
	<input type="hidden" name="cart_order_id" value="<?=$product_id?>">
	<input type="hidden" name="product_type" value="<?=$product_type?>">
	
<input type="hidden" name="c_prod" value="<?=$product_id?>">
<input type="hidden" name="c_name" value="<?=$product_name?>">
<input type="hidden" name="c_price" value="<?=$product_total?>">

	<input type="hidden" name="fixed" value="Y">
	<input type="hidden" name="id_type" value="1">
	<input type="hidden" name="sh_cost" value="0">
	<input type="hidden" name="demo" value="N">
	<input type="hidden" name="payment" value="2checkout">
</form>

<?
}
}
?>
