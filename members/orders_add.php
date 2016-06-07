<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?
$userbalance=0;

//Orders total
$sbt=(float)$_SESSION["product_subtotal"];
$shipping=(float)$_SESSION["product_shipping"];
$shipping_method=(int)$_SESSION["product_shipping_type"];
$weight=(float)$_SESSION["weight"];
$dsc=(float)$_SESSION["product_discount"];
$taxes=(float)$_SESSION["product_tax"];
$ttl=(float)$_SESSION["product_total"];

unset($_SESSION["checkout_steps"]);

//Credits balance
$credits=credits_balance();

//User_balance
//$userbalance=user_balance();
$userbalance=0;

//Check if we have credits
if($global_settings["credits"] and $ttl>$credits+$userbalance)
{
	if(!$global_settings["credits_currency"] or ($global_settings["credits_currency"] and @$_SESSION["checkout_method"]=="credits"))
	{
		header("location:credits.php");
		exit();
	}
}






//Insert new order
$taxes_info = array();
$order_id=order_add($sbt,$dsc,$ttl,$shipping,$taxes,$shipping_method,$weight);


//Use a coupon
if(isset($_SESSION["coupon_code"]))
{
	coupons_delete($_SESSION["coupon_code"]);
	//unset($_SESSION["coupon_code"]);
}





//Payout
$refund=0;
if($userbalance>0)
{
	if($ttl>=$userbalance){$refund=$userbalance;}
	if($ttl<$userbalance){$refund=$ttl;}
	$sql="insert into commission (total,user,orderid,item,publication,types,data,gateway,description) values (".(-1*$refund).",".(int)$_SESSION["people_id"].",".$order_id.",0,0,'refund',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'Website','New order #".$order_id."')";
	$db->execute($sql);
}



//IF ORDER APPROVED
if(!$global_settings["credits"] or ($global_settings["credits_currency"] and @$_SESSION["checkout_method"]=="currency"))
{
	/*
	if($ttl<=$userbalance)
	{
		order_approve($order_id);
		commission_add($order_id);
		coupons_add($_SESSION["people_login"]);
		send_notification('neworder_to_user',$order_id);
		send_notification('neworder_to_admin',$order_id);
	}
	*/
}
else
{
	order_approve($order_id);
	commission_add($order_id);
	if($ttl>$refund)
	{
		credits_delete($ttl-$refund,$order_id);
	}
	coupons_add($_SESSION["people_login"]);
	send_notification('neworder_to_user',$order_id);
	send_notification('neworder_to_admin',$order_id);
}


$yandex_payments="";
if(isset($_POST["yandex_payments"]))
{
	$yandex_payments="&yandex_payments=".result($_POST["yandex_payments"]);
}

$telephone="";
if(isset($_POST["telephone"]))
{
	$telephone="&telephone=".result($_POST["telephone"]);
}

$moneyua_method="";
if(isset($_POST["moneyua_method"]))
{
	$moneyua_method="&moneyua_method=".result($_POST["moneyua_method"]);
}

$targetpay_banks="";
if(isset($_POST["bank"]))
{
	$targetpay_banks="&targetpay_banks=".result($_POST["bank"]);
}


if($ttl==0)
{
	order_approve($order_id);
	commission_add($order_id);
	coupons_add($_SESSION["people_login"]);
	send_notification('neworder_to_user',$order_id);
	send_notification('neworder_to_admin',$order_id);
	if(!$global_settings["printsonly"])
	{
		header("location:profile_downloads.php");
	}
	else
	{
		header("location:orders.php");
	}
	exit();
}



//if($ttl>$userbalance and !$global_settings["credits"])
if(!$global_settings["credits"] or ($global_settings["credits_currency"] and @$_SESSION["checkout_method"]=="currency"))
{
	unset($_SESSION["checkout_method"]);
	header("location:payments_process.php?order_id=".$order_id."&payment=".result($_POST["payment"])."&tip=order".$telephone.$moneyua_method.$yandex_payments.$targetpay_banks);
}
else
{
	unset($_SESSION["checkout_method"]);
	
	if(!$global_settings["printsonly"])
	{
		header("location:profile_downloads.php");
	}
	else
	{
		header("location:orders.php");
	}
}

$db->close();
?>