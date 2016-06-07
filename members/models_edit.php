<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?
//Upload function
include("../admin/function/upload.php");

$swait=false;
if($global_settings["model"]==1)
{
	$sql="select * from models where user='".result($_SESSION["people_login"])."' and id_parent=".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$id=(int)$_GET["id"];

		$sql="update models set name='".result($_POST["title"])."',description='".result($_POST["description"])."' where id_parent=".(int)$_GET["id"];
		$db->execute($sql);
		
		$swait=model_upload($id);
	}
}

$db->close();

//go to back
redirect_file("models.php",$swait);
?>