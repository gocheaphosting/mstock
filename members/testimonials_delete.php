<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?
$sql="select id_parent,fromuser,data,content from testimonials where fromuser='".result($_SESSION["people_login"])."'";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["m".$rs->row["id_parent"]]))
	{
		$sql="delete from testimonials where id_parent=".$rs->row["id_parent"]." and  fromuser='".result($_SESSION["people_login"])."'";
		$db->execute($sql);
	}
	$rs->movenext();
}

$db->close();

header("location:testimonials.php");
?>