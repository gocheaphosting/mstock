<?if(!defined("site_root")){exit();}?>
<?
if($site_ccbill_account!=""){




if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
if($site_ccbill_ipn==true)
{


if ((int)$_POST["reasonForDecline"]==0)
{


	if($_POST["product_type"]=="credits")
	{
		credits_approve($_POST["product_id"],'');
							send_notification('credits_to_user',$_POST["product_id"]);
send_notification('credits_to_admin',$_POST["product_id"]);
	}

	if($_POST["product_type"]=="subscription")
	{
		subscription_approve($_POST["product_id"]);
				send_notification('subscription_to_user',$_POST["product_id"]);
send_notification('subscription_to_admin',$_POST["product_id"]);
	}

header("location:".surl.site_root."/members/payments_result.php?d=1");
exit();
}
else
{
header("location:".surl.site_root."/members/payments_result.php?d=2");
exit();
}

}
}
else
{



$aproduct=0;

if($_POST["tip"]=="credits")
{
$sql="select * from gateway_ccbill where credits=".(int)$_POST["credits"];
$ds->open($sql);
if(!$ds->eof)
{
$aproduct=$ds->row["product_id"];
}
}


if($_POST["tip"]=="subscription")
{
$sql="select * from gateway_ccbill where subscription=".(int)$_POST["subscription"];
$ds->open($sql);
if(!$ds->eof)
{
$aproduct=$ds->row["product_id"];
}
}
?>















<form  name="process" id="process" action="<?=ccbill_url?>" method="post">


<input type=hidden name=clientAccnum value='<?=$site_ccbill_account?>'>

<input type=hidden name=clientSubacc value='<?=$site_ccbill_account2?>'>

<input type=hidden name=formName value='<?=$site_ccbill_account3?>'>

<input type=hidden name=subscriptionTypeId value='<?=$aproduct?>' >


<INPUT type=hidden name=product_id value="<?=$product_id?>">

<INPUT type=hidden name=product_type value="<?=$product_type?>">

</FORM>

















<?

}



}
?>