<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_prices");



$sql="select * from photos_formats order by id";
$rs->open($sql);
while(!$rs->eof)
{
	$sql="update photos_formats set enabled=".(int)@$_POST[$rs->row["photo_type"]]." where photo_type='".$rs->row["photo_type"]."'";
	$db->execute($sql);

	$rs->movenext();
}

$db->close();

header("location:index.php?d=1#formats");
?>