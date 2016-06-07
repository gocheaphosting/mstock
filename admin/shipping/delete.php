<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_shipping");

$sql="delete from shipping where id=".(int)$_GET["id"];
$db->execute($sql);
		
$sql="delete from shipping_regions where id_parent=".(int)$_GET["id"];
$db->execute($sql);

$sql="delete from shipping_ranges where id_parent=".(int)$_GET["id"];
$db->execute($sql);

$db->close();
	
header("location:index.php");
?>
