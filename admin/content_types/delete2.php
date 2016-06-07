<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_content_types");

$sql="select id_parent,priority,name from content_type where id_parent=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{

	$sql="update photos set content_type='".result($_POST["new_type"])."' where content_type='".$rs->row["name"]."'";
	$db->execute($sql);
		
	$sql="update videos set content_type='".result($_POST["new_type"])."' where content_type='".$rs->row["name"]."'";
	$db->execute($sql);
		
	$sql="update audio set content_type='".result($_POST["new_type"])."' where content_type='".$rs->row["name"]."'";
	$db->execute($sql);
		
	$sql="update vector set content_type='".result($_POST["new_type"])."' where content_type='".$rs->row["name"]."'";
	$db->execute($sql);
		
	
	$sql="delete from content_type where id_parent=".(int)$_GET["id"];
	$db->execute($sql);
}

$db->close();

header("location:index.php");
?>
