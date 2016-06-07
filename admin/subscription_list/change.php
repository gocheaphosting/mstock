<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_subscription");

$sql="select id_parent from subscription_list where id_parent=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
	$sql="select title from subscription where id_parent=".(int)$_POST["subscription"];
	$ds->open($sql);
	if(!$ds->eof)
	{
		$sql="update subscription_list set title='".$ds->row["title"]."',user='".result($_POST["user"])."',data1=".mktime(0,0,0,$_POST["m1"],$_POST["d1"],$_POST["y1"]).",data2=".mktime(23,59,59,$_POST["m2"],$_POST["d2"],$_POST["y2"]).",bandwidth=".(float)$_POST["bandwidth"].",bandwidth_limit=".(float)$_POST["bandwidth_limit"].",subscription=".(int)$_POST["subscription"].",bandwidth_daily_limit=".(int)$_POST["bandwidth_daily"]." where id_parent=".(int)$_GET["id"];
		$db->execute($sql);
	}
}

$db->close();

header("location:index.php");
?>
