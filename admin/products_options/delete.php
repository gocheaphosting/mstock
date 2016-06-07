<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_productsoptions");

$sql="delete from products_options where id=".(int)$_GET["id"];
$db->execute($sql);
		
$sql="delete from products_options_items where id_parent=".(int)$_GET["id"];
$db->execute($sql);

$db->close();
	
header("location:index.php");
?>
