<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_blockedip");

$sql="delete from users_ip_blocked where ip='".result($_GET["ip"])."'";
$db->execute($sql);

$db->close();

redirect("index.php");
?>
