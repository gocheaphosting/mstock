<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_watermark");



$sql="update settings set svalue='' where setting_key='watermark_photo'";
$db->execute($sql);

$db->close();

header("location:watermark.php");


?>