<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_rightsmanaged");



$sql="update rights_managed_structure set title='".result($_POST["title"])."',adjust='".result($_POST["adjust"])."',price=".(float)$_POST["price"]." where types=2 and id=".(int)$_GET["id_element"];
$db->execute($sql);

$db->close();

header("location:content.php?id=".$_GET["id"]);
?>