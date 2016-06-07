<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_ffmpeg");






$sql="update settings set svalue='".(int)@$_POST["cron"]."' where setting_key='ffmpeg_cron'";
$db->execute($sql);




$db->close();

header("location:ffmpeg_cron.php");
?>