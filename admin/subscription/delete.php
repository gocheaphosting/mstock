<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_subscription");




$sql="delete from structure where id=".(int)$_GET["id"];
$db->execute($sql);

$sql="delete from subscription where id_parent=".(int)$_GET["id"];
$db->execute($sql);



$db->close();

header("location:index.php");
?>