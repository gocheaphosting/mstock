<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_stockapi");

$email_settings=array('istockphoto_id','istockphoto_secret','istockphoto_contributor','istockphoto_query','istockphoto_site','istockphoto_affiliate','istockphoto_show');

for($i=0;$i<count($email_settings);$i++)
{
	$sql="update settings set svalue='".result($_POST[$email_settings[$i]])."' where setting_key='".$email_settings[$i]."'";
	$db->execute($sql);
}

$sql="update settings set svalue='" . (int)@$_POST[ 'istockphoto_api' ] . "' where setting_key='istockphoto_api'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'istockphoto_pages' ] . "' where setting_key='istockphoto_pages'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'istockphoto_prints' ] . "' where setting_key='istockphoto_prints'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'istockphoto_files' ] . "' where setting_key='istockphoto_files'";
$db->execute($sql);

$db->close();

header("location:index.php?d=1");
?>
