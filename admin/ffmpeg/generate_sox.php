<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_ffmpeg");


if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/sox_preview.mp3"))
{
	@unlink($_SERVER["DOCUMENT_ROOT"].site_root."/content/sox_preview.mp3");
}


generate_mp3($_SERVER["DOCUMENT_ROOT"].site_root."/content/sox_test.mp3",$_SERVER["DOCUMENT_ROOT"].site_root."/content/sox_preview.mp3");

$db->close();

header("location:sox.php?p=1");
?>