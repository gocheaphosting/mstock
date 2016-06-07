<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_rightsmanaged");



$sql="update rights_managed_structure set title='".result($_POST["title"])."' where types=0 and id=".(int)$_GET["id_element"];
$db->execute($sql);

$db->close();

header("location:content.php?id=".$_GET["id"]);
?>