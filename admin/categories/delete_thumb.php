<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("catalog_categories");

$sql="update category set photo='' where id_parent=".(int)$_GET["id"];
$db->execute($sql);

$db->close();

header("location:content.php?id=".(int)$_GET["id"]);
?>