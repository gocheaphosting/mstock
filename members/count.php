<?
include("../admin/function/db.php");
include("download_mimes.php");
if(!isset($_SESSION["people_id"]) and $global_settings["auth_freedownload"])
{
	header("location:login.php");
	exit();
}

$publication_id=(int)$_GET["id_parent"];
$publication_item=(int)$_GET["id"];
$publication_type=result($_GET["type"]);
$publication_server=(int)@$_GET["server"];
$download_regime="subscription";


include("download_process.php");
?>