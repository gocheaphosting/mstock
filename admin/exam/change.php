<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_exam");

$sql="select * from examinations where id=".(int)$_GET["id"];
$ds->open($sql);
if(!$ds->eof)
{
	$sql="update examinations set status=".(int)$_POST["status"].",comments='".result($_POST["comments"])."' where id=".(int)$_GET["id"];
	$db->execute($sql);

	if((int)$_POST["status"]==1)
	{
		$sql="update users set examination=1 where id_parent=".$ds->row["user"];
		$db->execute($sql);

		$sql="update photos set examination=0 where userid=".$ds->row["user"];
		$db->execute($sql);

		$sql="update videos set examination=0 where userid=".$ds->row["user"];
		$db->execute($sql);

		$sql="update audio set examination=0 where userid=".$ds->row["user"];
		$db->execute($sql);

		$sql="update vector set examination=0 where userid=".$ds->row["user"];
		$db->execute($sql);
	}
	else
	{
		$sql="update users set examination=0 where id_parent=".$ds->row["user"];
		$db->execute($sql);

		$sql="update photos set examination=1 where userid=".$ds->row["user"];
		$db->execute($sql);

		$sql="update videos set examination=1 where userid=".$ds->row["user"];
		$db->execute($sql);

		$sql="update audio set examination=1 where userid=".$ds->row["user"];
		$db->execute($sql);

		$sql="update vector set examination=1 where userid=".$ds->row["user"];
		$db->execute($sql);
	}

	send_notification('exam_to_seller',$ds->row["user"],$ds->row["id"],"","");	
}

$db->close();

header("location:index.php");
?>