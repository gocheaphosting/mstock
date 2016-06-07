<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_licenses");


//If the category is new
if(isset($_GET["id"]) and (int)$_GET["id"]!=0)
{
	$sql="update licenses set name='".result($_POST["name"])."',priority=".(int)$_POST["priority"].",description='".result_html($_POST["description"])."' where id_parent=".(int)$_GET["id"];
	$db->execute($sql);
}
else
{	
	$sql="insert into licenses (name,priority,description) values ('".result($_POST["name"])."',".(int)$_POST["priority"].",'".result_html($_POST["description"])."')";
	$db->execute($sql);
}

$db->close();

header("location:index.php");
?>