<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_rightsmanaged");

$sql="delete from rights_managed_groups where id=".(int)$_GET["id"];
$db->execute($sql);
		
$sql="delete from rights_managed_options where id_parent=".(int)$_GET["id"];
$db->execute($sql);

$db->close();
	
header("location:index.php?d=2");
?>
