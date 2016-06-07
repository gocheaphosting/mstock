<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?
$sql="select id_parent,user,data,content from blog_comments where user='".result($_SESSION["people_login"])."'";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["m".$rs->row["id_parent"]]))
	{
		$sql="delete from blog_comments where id_parent=".$rs->row["id_parent"]." and  user='".result($_SESSION["people_login"])."'";
		$db->execute($sql);

		$sql="delete from structure where id=".$rs->row["id_parent"];
		$db->execute($sql);
	}
	$rs->movenext();
}

$db->close();

header("location:blog_comments.php");
?>