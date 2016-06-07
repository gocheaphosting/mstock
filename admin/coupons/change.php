<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_coupons");


$coupon_code=result($_POST["code"]);
$flag=true;

if($coupon_code!="")
{
	$sql="select coupon_code from coupons where coupon_code='".$coupon_code."' and id_parent<>".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$flag=false;
	}
}
else
{
	$flag=false;
}

if(!$flag)
{
	$coupon_code=md5(create_password().mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")));
}


$sql="select * from coupons where id_parent=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
	$sql="update coupons set title='".result($_POST["title"])."',user='".result($_POST["user"])."',total=".(float)$_POST["total"].",percentage=".(float)$_POST["percentage"].",url='".result($_POST["url"])."',data=".($rs->row["data2"]+(int)$_POST["days"]*3600*24).",ulimit=".(int)$_POST["limit_of_usage"].",coupon_code='".$coupon_code."' where id_parent=".(int)$_GET["id"];
	$db->execute($sql);
}

	
header("location:index.php");

?>
