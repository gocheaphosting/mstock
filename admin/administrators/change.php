<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_administrators");

$id=0;

$sql="update templates_admin set activ=0";
$db->execute($sql);

$sql="update templates_admin set activ=1 where id=".(int)$_POST["activ"];
$db->execute($sql);

$db->close();
	
header("location:select_theme.php");
?>
