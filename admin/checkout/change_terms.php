<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_checkout");


$sql="select * from terms";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["delete".$rs->row["id"]]))
	{
		$sql="delete from terms where id=".$rs->row["id"];
		$db->execute($sql);
	}
	else
	{
		$sql="update terms set title='".result($_POST["title".$rs->row["id"]])."',types=".(int)$_POST["types".$rs->row["id"]].",priority=".(int)$_POST["priority".$rs->row["id"]].",page_id=".(int)$_POST["page_id".$rs->row["id"]]." where id=".$rs->row["id"];
		$db->execute($sql);	
	}
	
	$rs->movenext();
}

$db->close();

header("location:index.php");
?>
