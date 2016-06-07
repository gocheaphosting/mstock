<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("catalog_upload");

delete_category((int)$_GET["id"],0);

$smarty->clearCache(null,"buildmenu");

$db->close();

header("location:index.php?d=1");
?>