<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_support");

$sql="delete from support_tickets where id=".(int)$_GET["id"]." or id_parent=".(int)$_GET["id"];
$db->execute($sql);



header("location:index.php");
?>
