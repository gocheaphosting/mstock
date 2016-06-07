<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_site");



$sql="select * from settings";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["a_".$rs->row["setting_key"]]))
	{
		$sql="update settings set svalue='".result($_POST["p_".$rs->row["setting_key"]])."',activ=".result($_POST["a_".$rs->row["setting_key"]])." where id=".$rs->row["id"];
		$db->execute($sql);
	}
	else
	{	
		if(isset($_POST["p_".$rs->row["setting_key"]]))
		{
			$sql="update settings set svalue='".result($_POST["p_".$rs->row["setting_key"]])."',activ=0 where id=".$rs->row["id"];
			$db->execute($sql);
		}
	}
	$rs->movenext();
}

$db->close();

header("location:site.php");
?>