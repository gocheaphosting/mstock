<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_ffmpeg");


$dir = opendir ($DOCUMENT_ROOT.site_upload_directory);
while ($file = readdir ($dir)) 
{
	if($file <> "." && $file <> "..")
	{
		if(preg_match("/thumb/i",$file)) 
		{
			@unlink($DOCUMENT_ROOT.site_upload_directory."/".$file);
		}
	}
}
closedir ($dir);


generate_flv($_SERVER["DOCUMENT_ROOT"].site_root."/content/ffmpeg_test.wmv",0,0);

$db->close();

header("location:index.php?p=1");
?>