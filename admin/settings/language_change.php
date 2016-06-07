<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_languages");



$sql="update languages set activ=0,display=0";
$db->execute($sql);

$sql="update languages set activ=1 where name='".result($_POST["language"])."'";
$db->execute($sql);


$sql="select * from languages";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST[str_replace(" ","_",strtolower($rs->row["name"]))]))
	{
		$sql="update languages set display=1 where name='".$rs->row["name"]."'";
		$db->execute($sql);
	}

	$rs->movenext();
}

unset($_SESSION["site_lng"]);
unset($_SESSION["site_lng_activ"]);
unset($_SESSION["site_mtg_activ"]);

$smarty->clearAllCache();

$db->close();

header("location:languages.php");
?>