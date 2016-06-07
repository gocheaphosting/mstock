<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){exit;}?>
<? include("../admin/function/upload.php");?>
<?if($global_settings["userupload"]==0){exit;}?>
<?


delete_category((int)$_GET["id"],(int)$_SESSION["people_id"]);

$smarty->clearCache(null,"buildmenu");

$db->close();

header("location:publications.php?d=1");
?>