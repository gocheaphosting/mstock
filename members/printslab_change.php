<?
include("../admin/function/db.php");
if(!isset($_SESSION["people_id"])){header("location:login.php");}

$sql="update galleries set title='".result($_POST["title"])."' where user_id=".(int)$_SESSION["people_id"]." and id=".(int)$_GET["id"];
$db->execute($sql);

$sql="select id from galleries where id=".(int)$_GET["id"]." and user_id=".(int)$_SESSION["people_id"];
$ds->open($sql);
if(!$ds->eof)
{
	
	$sql="select * from galleries_photos where id_parent='".(int)$_GET["id"]."' order by data";
	$rs->open($sql);
	while(!$rs->eof)
	{
		if(isset($_POST["delete".$rs->row["id"]]))
		{
			$sql="delete from galleries_photos where id=".$rs->row["id"];
			$db->execute($sql);
			
			@unlink($_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".(int)$_GET["id"]."/".$rs->row["photo"]);	
			@unlink($_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".(int)$_GET["id"]."/thumb".$rs->row["id"].".jpg");	
		}
		else
		{
			$sql="update galleries_photos set title='".result($_POST["title".$rs->row["id"]])."'  where id=".$rs->row["id"];
			$db->execute($sql);
		}
		$rs->movenext();
	}
}

header("location:printslab.php");
?>

