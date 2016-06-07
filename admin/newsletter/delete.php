<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_newsletter");

$sql="delete from newsletter where id=".(int)$_GET["id"];
$db->execute($sql);

$db->close();

header("location:index.php?d=2");
?>