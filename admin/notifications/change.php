<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_notifications");

$sql="select * from notifications order by priority";
$rs->open($sql);
while(!$rs->eof)
{
	$enabled=0;
	if(isset($_POST["e".$rs->row["events"]]))
	{
		$enabled=1;
	}

	$sql="update notifications set enabled=".$enabled." where events='".$rs->row["events"]."'";
	$db->execute($sql);

	$rs->movenext();
}

$db->close();

header("location:index.php");
?>
