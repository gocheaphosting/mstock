<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_upload");

publication_delete((int)$_GET["id"]);

$db->close();

header("location:index.php?d=".$_GET["d"]);
?>