<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_video");

$sql="select * from video_frames";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["m".$rs->row["id"]]))
	{
		$sql="delete from video_frames where id=".$rs->row["id"];
		$db->execute($sql);
	}
	else
	{
		$sql="update video_frames set name='".result($_POST["title".$rs->row["id"]])."' where id=".$rs->row["id"];
		$db->execute($sql);
	}
	$rs->movenext();
}
$db->close();

header("location:index.php?d=4");
?>