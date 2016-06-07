<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("templates_home");

$sql="delete from components where id=".(int)$_GET["id"];
$db->execute($sql);

$db->close();

header("location:index.php");
?>