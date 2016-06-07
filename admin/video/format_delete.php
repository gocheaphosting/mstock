<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_video");

$sql="select * from video_format";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["m".$rs->row["id"]]))
	{
		$sql="delete from video_format where id=".$rs->row["id"];
		$db->execute($sql);
	}
	else
	{
		$sql="update video_format set name='".result($_POST["title".$rs->row["id"]])."' where id=".$rs->row["id"];
		$db->execute($sql);
	}
$rs->movenext();
}



$db->close();


header("location:index.php?d=1");




?>