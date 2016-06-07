<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_creditstypes");


$sql="delete from credits where id_parent=".(int)$_GET["id"];
$db->execute($sql);

$db->close();

header("location:index.php");
?>
