<? 
include("../function/db.php");

//Check access
admin_panel_access("settings_pwinty");

$sql="select id_parent from prints order by priority";
$rs->open($sql);
while(!$rs->eof)
{	
	if(isset($_POST["print".$rs->row["id_parent"]]))
	{
		$sql="update pwinty_prints set activ=1,title='".result($_POST["title".$rs->row["id_parent"]])."' where print_id=".$rs->row["id_parent"];
	}
	else
	{
		$sql="update pwinty_prints set activ=0,title='".result($_POST["title".$rs->row["id_parent"]])."' where print_id=".$rs->row["id_parent"];
	}

	$db->execute($sql);
	
	$rs->movenext();
}

$db->close();

header("location:index.php?d=1");
?>