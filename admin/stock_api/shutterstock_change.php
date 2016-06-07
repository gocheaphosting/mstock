<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_stockapi");

$email_settings=array('shutterstock_id','shutterstock_secret','shutterstock_affiliate','shutterstock_contributor','shutterstock_category','shutterstock_show');

for($i=0;$i<count($email_settings);$i++)
{
	$sql="update settings set svalue='".result($_POST[$email_settings[$i]])."' where setting_key='".$email_settings[$i]."'";
	$db->execute($sql);
}

$sql="update settings set svalue='" . (int)@$_POST[ 'shutterstock_api' ] . "' where setting_key='shutterstock_api'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'shutterstock_pages' ] . "' where setting_key='shutterstock_pages'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'shutterstock_prints' ] . "' where setting_key='shutterstock_prints'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'shutterstock_files' ] . "' where setting_key='shutterstock_files'";
$db->execute($sql);

$db->close();

header("location:index.php?d=2");
?>
