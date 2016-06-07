<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?
//Upload function
include("../admin/function/upload.php");

if($global_settings["model"]==1)
{
	model_delete((int)$_GET["id"],result($_SESSION["people_login"]));
}

$db->close();

header("location:models.php");
?>