<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_ffmpeg");






$sql="update settings set svalue='".(int)@$_POST["sox"]."' where setting_key='sox'";
$db->execute($sql);


$sql="update settings set svalue='".result($_POST["fpath"])."' where setting_key='sox_path'";
$db->execute($sql);


$sql="update settings set svalue='".(int)$_POST["duration"]."' where setting_key='sox_duration'";
$db->execute($sql);

$sql="update settings set svalue='".result($_POST["library"])."' where setting_key='sox_library'";
$db->execute($sql);

$sql="update settings set svalue='".(int)@$_POST["watermark_enable"]."' where setting_key='sox_watermark'";
$db->execute($sql);


if($_FILES['watermark']['size']>0)
{
	if(2048*1024>=$_FILES['watermark']['size'])
	{
		if(strtolower(get_file_info($_FILES['watermark']['name'],"extention"))=="mp3")
		{
			move_uploaded_file($_FILES['watermark']['tmp_name'],$DOCUMENT_ROOT.site_upload_directory."/watermark.mp3");
			
			$sql="update settings set svalue='".site_root."/content/watermark.mp3' where setting_key='sox_watermark_file'";
			$db->execute($sql);
		}	
	}	
}



$db->close();

header("location:sox.php");
?>