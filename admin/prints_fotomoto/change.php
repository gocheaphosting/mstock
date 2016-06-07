<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_fotomoto");

$sql="update settings set svalue='".result($_POST["fotomoto_id"])."' where setting_key='fotomoto_id'";
$db->execute($sql);


$db->close();

header("location:index.php");
?>
