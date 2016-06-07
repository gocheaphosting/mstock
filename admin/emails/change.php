<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_emails");

$email_settings=array('admin_email','from_email','mailtype','smtp_user','smtp_password','smtp_port','smtp_server');

for($i=0;$i<count($email_settings);$i++)
{
	$sql="update settings set svalue='".result($_POST[$email_settings[$i]])."' where setting_key='".$email_settings[$i]."'";
	$db->execute($sql);
}

$db->close();

header("location:index.php");
?>
