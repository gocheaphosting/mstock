<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_stockapi");

$email_settings=array('rf123_id','rf123_secret','rf123_affiliate','rf123_contributor','rf123_category','rf123_query','rf123_show');

for($i=0;$i<count($email_settings);$i++)
{
	$sql="update settings set svalue='".result($_POST[$email_settings[$i]])."' where setting_key='".$email_settings[$i]."'";
	$db->execute($sql);
}

$sql="update settings set svalue='" . (int)@$_POST[ 'rf123_api' ] . "' where setting_key='rf123_api'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'rf123_pages' ] . "' where setting_key='rf123_pages'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'rf123_prints' ] . "' where setting_key='rf123_prints'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'rf123_files' ] . "' where setting_key='rf123_files'";
$db->execute($sql);

$db->close();

header("location:index.php?d=5");
?>
