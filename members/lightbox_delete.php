<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?


//Check
$sql="select id_parent,user_owner from lightboxes_admin where user=".(int)$_SESSION["people_id"]." and id_parent=".(int)$_GET["id"]." and  user_owner=1";
$rs->open($sql);
if($rs->eof)
{
	exit();
}

$sql="delete from lightboxes where id=".(int)$_GET["id"];
$db->execute($sql);

$sql="delete from lightboxes_admin where id_parent=".(int)$_GET["id"];
$db->execute($sql);

$sql="delete from lightboxes_files where id_parent=".(int)$_GET["id"];
$db->execute($sql);

$db->close();

header("location:lightbox.php");
?>