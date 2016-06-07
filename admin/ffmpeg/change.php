<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_ffmpeg");

$ffmpeg=0;


if(isset($_POST["ffmpeg"])){$ffmpeg=1;}







$sql="update settings set svalue='".$ffmpeg."' where setting_key='ffmpeg'";
$db->execute($sql);

$sql="update settings set svalue='".(int)$_POST["video_width"]."' where setting_key='ffmpeg_video_width'";
$db->execute($sql);

$sql="update settings set svalue='".(int)$_POST["video_height"]."' where setting_key='ffmpeg_video_height'";
$db->execute($sql);

$sql="update settings set svalue='".result($_POST["fpath"])."' where setting_key='ffmpeg_path'";
$db->execute($sql);

$sql="update settings set svalue='".(int)$_POST["thumb_width"]."' where setting_key='ffmpeg_thumb_width'";
$db->execute($sql);

$sql="update settings set svalue='".(int)$_POST["thumb_height"]."' where setting_key='ffmpeg_thumb_height'";
$db->execute($sql);

$sql="update settings set svalue='".(int)$_POST["frequency"]."' where setting_key='ffmpeg_frequency'";
$db->execute($sql);

$sql="update settings set svalue='".(int)$_POST["duration"]."' where setting_key='ffmpeg_duration'";
$db->execute($sql);

$sql="update settings set svalue='".result($_POST["video_format"])."' where setting_key='ffmpeg_video_format'";
$db->execute($sql);

$sql="update settings set svalue='".(int)@$_POST["watermark"]."' where setting_key='ffmpeg_watermark'";
$db->execute($sql);



$db->close();

header("location:index.php");
?>