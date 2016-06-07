<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_ffmpeg");

$sql="select id from ffmpeg_cron order by data1 desc";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["delete".$rs->row["id"]]))
	{
		$sql="delete from ffmpeg_cron where id=".$rs->row["id"];
		$db->execute($sql);
	}
	$rs->movenext();
}





if(isset($_SERVER["HTTP_REFERER"]))
{
	$return_url=$_SERVER["HTTP_REFERER"];
}
else
{
	$return_url="index.php";
}

$db->close();

redirect($return_url);
?>
