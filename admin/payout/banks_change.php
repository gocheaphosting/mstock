<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_payout");


$sql="select * from banks order by title";
$rs->open($sql);
while(!$rs->eof)
{
	$sql="update banks set title='".result($_POST["title".$rs->row["id"]])."' where id=".$rs->row["id"];
	$db->execute($sql);
	
	if(isset($_POST["delete".$rs->row["id"]]))
	{
		$sql="delete from banks where id=".$rs->row["id"];
		$db->execute($sql);
	}
	
	$rs->movenext();
}

$db->close();

header("location:index.php?d=3");
?>