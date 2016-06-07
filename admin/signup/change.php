<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_signup");



$sql="update users_settings set activ=0";
$db->execute($sql);

$sql="update users_settings set activ=1 where svalue='".result($_POST["activ"])."'";
$db->execute($sql);

$db->close();

header("location:index.php");
?>