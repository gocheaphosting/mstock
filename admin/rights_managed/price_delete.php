<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_rightsmanaged");


$sql="delete from rights_managed where id=".(int)$_GET["id"];
$db->execute($sql);

$sql="delete from rights_managed_structure where price_id=".(int)$_GET["id"];
$db->execute($sql);

$db->close();

header("location:index.php");
?>