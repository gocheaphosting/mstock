<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_exam");

$sql="delete from examinations where id=".(int)$_GET["id"];
$db->execute($sql);

$db->close();

header("location:index.php");
?>