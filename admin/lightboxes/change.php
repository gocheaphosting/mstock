<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_lightboxes");


$sql="update lightboxes set title='".result($_POST["title"])."',catalog=".(int)@$_POST["catalog"]." where id=".(int)$_GET["id"];
$db->execute($sql);


$db->close();

redirect("index.php	");
?>
