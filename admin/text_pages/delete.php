<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("pages_textpages");

$sql="delete from pages where id_parent=".(int)$_GET["id"];
$db->execute($sql);

unset($_SESSION["site_info_content"]);

$db->close();

header("location:index.php");
?>