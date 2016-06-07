<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_stockapi");

$email_settings=array('stock_default');

for($i=0;$i<count($email_settings);$i++)
{
	$sql="update settings set svalue='".result($_POST[$email_settings[$i]])."' where setting_key='".$email_settings[$i]."'";
	$db->execute($sql);
}

$sql="update settings set svalue='" . (int)@$_POST[ 'site_api' ] . "' where setting_key='site_api'";
$db->execute($sql);

unset($_SESSION["stock_selected"]);

$db->close();

header("location:index.php?d=0");
?>
