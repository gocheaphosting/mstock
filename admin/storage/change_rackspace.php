<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_storage");


$activ=0;
if(isset($_POST["activ"]))
{
	$activ=1;
}

$sql="update settings set svalue='".$activ."' where setting_key='rackspace'";
$db->execute($sql);

$sql="update settings set svalue='".result($_POST["prefix"])."' where setting_key='rackspace_prefix'";
$db->execute($sql);

$sql="update settings set svalue='".result($_POST["username"])."' where setting_key='rackspace_username'";
$db->execute($sql);

$sql="update settings set svalue='".result($_POST["api_key"])."' where setting_key='rackspace_api_key'";
$db->execute($sql);



$db->close();

redirect("index.php?d=3");
?>