<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_catalog");


$sql="update content_filter set words='".result($_POST["filter"])."'";
$db->execute($sql);

$db->close();

header("location:filter.php");
?>

