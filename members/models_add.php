<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?
//Upload function
include("../admin/function/upload.php");


$swait=false;
if($global_settings["model"]==1)
{
	$sql="insert into models  (name,description,user) values ('".result($_POST["title"])."','".result($_POST["description"])."','".result($_SESSION["people_login"])."')";
	$db->execute($sql);
	
	$id=0;
	$sql="select id_parent from models where user='".result($_SESSION["people_login"])."' order by id_parent desc";
	$ds->open($sql);
	if(!$ds->eof)
	{
		$id=$ds->row["id_parent"];
	}

	$swait=model_upload($id);
}



$db->close();



//go to back
redirect_file("models.php",$swait);
?>