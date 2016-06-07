<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_support");

$sql="delete from support_tickets where id=".(int)$_GET["id"];
$db->execute($sql);



header("location:content.php?id=".(int)$_GET["id_parent"]);
?>
