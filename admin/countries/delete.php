<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_countries");


$sql="delete from countries where id=".(int)$_GET["id"];
$db->execute($sql);

header("location:index.php");
?>
