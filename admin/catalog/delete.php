<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("catalog_catalog");

if(!$demo_mode)
{
	publication_delete((int)$_GET["id"]);
}

$db->close();

if(isset($_SERVER["HTTP_REFERER"]))
{
	header("location:".$_SERVER["HTTP_REFERER"]);
}
else
{
	header("location:index.php");
}
?>