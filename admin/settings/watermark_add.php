<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_watermark");

if($_FILES['watermark']['size']>0)
{
	if(2048*1024>=$_FILES['watermark']['size'])
	{
		if(strtolower(get_file_info($_FILES['watermark']['name'],"extention"))=="png")
		{
			move_uploaded_file($_FILES['watermark']['tmp_name'],$DOCUMENT_ROOT.site_upload_directory."/watermark.png");
			
			$sql="update settings set svalue='".site_root.site_upload_directory."/watermark.png' where setting_key='watermark_photo'";
			$db->execute($sql);
		}	
	}	
}


$sql="update settings set svalue=".(int)$_POST["position"]." where setting_key='watermark_position'";
$db->execute($sql);

$db->close();

header("location:watermark.php");


?>