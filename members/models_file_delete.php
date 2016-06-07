<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?
//Upload function
include("../admin/function/upload.php");

if($global_settings["model"]==1)
{
	if(isset($_GET["type"]) and $_GET["type"]=="modelphoto")
	{
		model_delete_file((int)$_GET["id"],"photo",result($_SESSION["people_login"]));
	}
	if(isset($_GET["type"]) and $_GET["type"]=="model")
	{
		model_delete_file((int)$_GET["id"],"file",result($_SESSION["people_login"]));
	}
}

$db->close();

header("location:models_content.php?id=".$_GET["id"]);
?>