<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_countries");



$sql="select * from countries";
$rs->open($sql);
while(!$rs->eof)
{
	$sql="update countries set priority=".(int)$_POST["priority".$rs->row["id"]].",activ=".(int)@$_POST["country".$rs->row["id"]]." where id=".$rs->row["id"];
	$db->execute($sql);
	
	$rs->movenext();
}

header("location:index.php");
?>
