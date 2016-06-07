<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_video");

$sql="select * from video_ratio";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["m".$rs->row["id"]]))
	{
		$sql="delete from video_ratio where id=".$rs->row["id"];
		$db->execute($sql);
	}
	else
	{
		$sql="update video_ratio set name='".result($_POST["title".$rs->row["id"]])."',width=".(int)$_POST["width".$rs->row["id"]].",height=".(int)$_POST["height".$rs->row["id"]]." where id=".$rs->row["id"];
		$db->execute($sql);
	}
$rs->movenext();
}



$db->close();


header("location:index.php?d=2");




?>