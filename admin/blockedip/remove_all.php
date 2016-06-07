<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_blockedip");

$sql="delete from users_ip_blocked";
$db->execute($sql);

$db->close();

redirect("index.php");
?>
