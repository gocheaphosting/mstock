<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_taxes");



$sql="delete from tax where id=".(int)$_GET["id"];
$db->execute($sql);

$sql="delete from tax_regions where id_parent=".(int)$_GET["id"];
$db->execute($sql);

$db->close();

header("location:index.php");
?>