<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?
$sql="select id from examinations where user=".(int)$_SESSION["people_id"];
$rs->open($sql);
if(!$rs->eof)
{
	$sql="update examinations set data=".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." where id=".$rs->row["id"];
	$db->execute($sql);
	send_notification('exam_to_admin',(int)$_SESSION["people_id"],$rs->row["id"],"","");	
}
else
{
	$sql="insert into examinations (user,data,status) values (".(int)$_SESSION["people_id"].",".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",0)";
	$db->execute($sql);
	
	$sql="select id from examinations where user=".(int)$_SESSION["people_id"];
	$ds->open($sql);
	if(!$ds->eof)
	{
		send_notification('exam_to_admin',(int)$_SESSION["people_id"],$ds->row["id"],"","");	
	}
}

$db->close();

header("location:upload.php?t=1");
?>