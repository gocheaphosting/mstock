<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_rightsmanaged");



include("rights_managed_functions.php");

$nlimit=0;
delete_rights_managed_admin((int)$_GET["id_element"]);

$db->close();

header("location:content.php?id=".$_GET["id"]);
?>