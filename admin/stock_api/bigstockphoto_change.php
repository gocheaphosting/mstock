<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_stockapi");

$email_settings=array('bigstockphoto_id','bigstockphoto_affiliate','bigstockphoto_contributor','bigstockphoto_category','bigstockphoto_show');

for($i=0;$i<count($email_settings);$i++)
{
	$sql="update settings set svalue='".result($_POST[$email_settings[$i]])."' where setting_key='".$email_settings[$i]."'";
	$db->execute($sql);
}

$sql="update settings set svalue='" . (int)@$_POST[ 'bigstockphoto_api' ] . "' where setting_key='bigstockphoto_api'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'bigstockphoto_pages' ] . "' where setting_key='bigstockphoto_pages'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'bigstockphoto_prints' ] . "' where setting_key='bigstockphoto_prints'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'bigstockphoto_files' ] . "' where setting_key='bigstockphoto_files'";
$db->execute($sql);

$db->close();

header("location:index.php?d=6");
?>
