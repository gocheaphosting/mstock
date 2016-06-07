<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_content_types");

$sql="select id_parent,priority,name from content_type order by priority";
$rs->open($sql);
while(!$rs->eof)
{
	if(result($_POST["title".$rs->row["id_parent"]])!=$rs->row["name"])
	{
		$sql="update photos set content_type='".result($_POST["title".$rs->row["id_parent"]])."' where content_type='".$rs->row["name"]."'";
		$db->execute($sql);
		
		$sql="update videos set content_type='".result($_POST["title".$rs->row["id_parent"]])."' where content_type='".$rs->row["name"]."'";
		$db->execute($sql);
		
		$sql="update audio set content_type='".result($_POST["title".$rs->row["id_parent"]])."' where content_type='".$rs->row["name"]."'";
		$db->execute($sql);
		
		$sql="update vector set content_type='".result($_POST["title".$rs->row["id_parent"]])."' where content_type='".$rs->row["name"]."'";
		$db->execute($sql);
	}
		
	
	$sql="update content_type set name='".result($_POST["title".$rs->row["id_parent"]])."',priority=".(int)$_POST["priority".$rs->row["id_parent"]]." where id_parent=".$rs->row["id_parent"];
	$db->execute($sql);
	$rs->movenext();
}

$db->close();

header("location:index.php");
?>
