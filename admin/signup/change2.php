<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_signup");

$sql="update settings set svalue=".(int)$_POST["param"]." where setting_key='user_signup'";
$db->execute($sql);

$db->close();

header("location:index.php");
?>