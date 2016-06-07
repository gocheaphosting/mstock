<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_search");

$sql="delete from search_history";
$db->execute($sql);

$db->close();

header("location:index.php");
?>
