<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_licenses");


$sql="delete from licenses where id_parent=".(int)$_GET["id"];
$db->execute($sql);

$db->close();

header("location:index.php");
?>