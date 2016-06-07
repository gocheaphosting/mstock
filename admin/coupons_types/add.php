<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_couponstypes");


	if($_POST["discount_type"]=="total")
	{
		$com=",".(float)$_POST["discount"].",0";
	}
	if($_POST["discount_type"]=="percentage")
	{
		$com=",0,".(float)$_POST["discount"];
	}

	$sql="insert into coupons_types (title,days,total,percentage,url,events,ulimit,bonus) values ('".result($_POST["title"])."',".(int)$_POST["days"].$com.",'".result($_POST["url"])."','".result($_POST["events"])."',".(int)$_POST["ulimit"].",".(float)$_POST["bonus"].")";
	$db->execute($sql);

$db->close();

header("location:index.php");
?>