<?if(!defined("site_root")){exit();}?>
<?
if($site_multicards_account!=""){




if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
if($site_multicards_ipn==true)
{
$multicards_ok=true;


if($_POST["SilentPostPassword"]!=$site_multicards_account3){$multicards_ok=false;}
if((int)$_POST["mer_id"]!=$site_multicards_account){$multicards_ok=false;}
//if($_POST["status"]!="accepted"){$multicards_ok=false;}

echo("<!--success-->");
if ($multicards_ok==true)
{


	$transaction_id=transaction_add("multicards",$_POST["order_num"],$_POST["item1_desc"],$_POST["user1"]);

	if($_POST["item1_desc"]=="credits")
	{
		credits_approve($_POST["user1"],$transaction_id);
									send_notification('credits_to_user',$_POST["user1"]);
send_notification('credits_to_admin',$_POST["user1"]);
	}

	if($_POST["item1_desc"]=="subscription")
	{
		subscription_approve($_POST["user1"]);
								send_notification('subscription_to_user',$_POST["user1"]);
send_notification('subscription_to_admin',$_POST["user1"]);
	}

	if($_POST["item1_desc"]=="order")
	{
		order_approve($_POST["user1"]);
		commission_add($_POST["user1"]);
		coupons_add(order_user($_POST["user1"]));
		                    										send_notification('neworder_to_user',$_POST["user1"]);
send_notification('neworder_to_admin',$_POST["user1"]);
	}
//header("location:".surl.site_root."/members/payments_result.php?d=1");
//exit();
}
else
{
//header("location:".surl.site_root."/members/payments_result.php?d=2");
//exit();
}








}
}
else
{
?>



<form method="post" name="process" id="process" action="<?=multicards_url?>">
<input type=hidden name="mer_id" value="<?=$site_multicards_account?>">
<input type=hidden name="num_items" value="1">
<input type=hidden name="mer_url_idx" value="<?=$site_multicards_account2?>">
<input type=hidden name="item1_desc" value="<?=$product_type?>">
<input type=hidden name="item1_price" value="<?=$product_total?>">
<input type=hidden name="item1_qty" value="1">
<input type=hidden name="user1" value="<?=$product_id?>">
</form>



<?

}



}
?>