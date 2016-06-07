<?$site="coupons";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?
$sql="select id_parent,title,user,data2,total,	percentage,url,used,orderid,data,ulimit,tlimit,coupon_id,coupon_code from coupons where user='".result($_SESSION["people_login"])."' and url<>'' and used=0 and id_parent=".(int)$_GET["id"];

$rs->open($sql);
if(!$rs->eof)
{
	$used=0;
	if($rs->row["tlimit"]+1==$rs->row["ulimit"])
	{
		$used=1;
	}
	
	$sql="update coupons set tlimit=tlimit+1,used=".$used." where id_parent=".$rs->row["id_parent"];
	$db->execute($sql);

	header("location:".$rs->row["url"]);
}

$db->close();
?>