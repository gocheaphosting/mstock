<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_ffmpeg");


if($global_settings["sox_watermark_file"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$global_settings["sox_watermark_file"]))
{
	$sql="update settings set svalue='' where setting_key='sox_watermark_file'";
	$db->execute($sql);
	
	@unlink($_SERVER["DOCUMENT_ROOT"].$global_settings["sox_watermark_file"]);
}



$db->close();

header("location:sox.php");
?>