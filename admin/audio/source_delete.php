<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_audio");

$sql="select * from audio_source";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["m".$rs->row["id"]]))
	{
		$sql="delete from audio_source where id=".$rs->row["id"];
		$db->execute($sql);
	}
	else
	{
		$sql="update audio_source set name='".result($_POST["title".$rs->row["id"]])."' where id=".$rs->row["id"];
		$db->execute($sql);
	}
	$rs->movenext();
}

$db->close();

header("location:index.php?d=1");
?>