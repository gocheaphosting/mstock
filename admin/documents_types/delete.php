<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_documents");



	$sql="delete from documents_types where id=".(int)$_GET["id"];
	$db->execute($sql);

$db->close();

header("location:index.php");
?>