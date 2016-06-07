<?if(!defined("site_root")){exit();}?>
<?
if($site_eway_account!=""){

if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
if($site_eway_ipn==true)
{








if($_POST['ewayTrxnStatus'] == "True")
{

echo("Ñongratulation! The transaction is successfull.");

	$transaction_id=transaction_add("authorize",$_POST["ewayTrxnReference"],$_POST["eWAYoption1"],$_POST["eWAYoption2"]);

	if($_POST["eWAYoption1"]=="credits")
	{
					send_notification('credits_to_user',$_POST["eWAYoption2"]);
send_notification('credits_to_admin',$_POST["eWAYoption2"]);
		credits_approve($_POST["eWAYoption2"],$transaction_id);
	}
	
		if($_POST["eWAYoption1"]=="subscription")
	{
		subscription_approve($_POST["eWAYoption2"]);
		send_notification('subscription_to_user',$_POST["eWAYoption2"]);
send_notification('subscription_to_admin',$_POST["eWAYoption2"]);
	}

	if($_POST["eWAYoption1"]=="order")
	{
		order_approve($_POST["eWAYoption2"]);
		commission_add($_POST["eWAYoption2"]);
		coupons_add(order_user($_POST["eWAYoption2"]));
						send_notification('neworder_to_user',$_POST["eWAYoption2"]);
send_notification('neworder_to_admin',$_POST["eWAYoption2"]);

	}
header("header:payments_result.php?d=1");
}
else
{
header("header:payments_result.php?d=2");
}












}
else
{
header("header:payments_result.php?d=2");
}
}
else
{
?>

<form method="post" action="<?=eway_url?>"  name="process" id="process"> 
	<input type="hidden" name="ewayCustomerID" value="<?=$site_eway_account?>" />
	<input type="hidden" name="ewayTotalAmount" value="<?=($product_total*100)?>" />
	<input type="hidden" name="ewayCustomerInvoiceRef" value="<?=$product_id?>" />
		<input type="hidden" name="eWAYoption1" value="<?=$product_type?>" />
				<input type="hidden" name="eWAYoption2" value="<?=$product_id?>" />
<?
$sql="select name,email,telephone,address,country from users where id_parent=".(int)$_SESSION["people_id"];
$dr->open($sql);
if(!$dr->eof)
{
?>
	<input type="hidden" name="ewayCustomerFirstName" value="<?=$dr->row["name"]?>" />
	<input type="hidden" name="ewayCustomerLastName" value="" />
	<input type="hidden" name="ewayCustomerEmail" value="<?=$dr->row["email"]?>" />
	<input type="hidden" name="ewayCustomerAddress" value="<?=$dr->row["address"]?>" />
	<input type="hidden" name="ewayCustomerPostcode" value="" />
<?
}
?>


	<input type="hidden" name="ewayOption3" value="false" />
	<input type="hidden" name="ewayURL" value="<?=surl.site_root."/members/payments_process.php?mode=notification"?>&processor=eway" />

</form>

<?
}
}
?>
