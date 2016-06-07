<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_models");

	
if(!isset($_GET["type"]))
{
	model_delete_file((int)$_GET["id"],"photo","");
}
else
{
	model_delete_file((int)$_GET["id"],"file","");
}

$db->close();

header("location:content.php?id=".(int)$_GET["id"]);
?>