<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_stockapi");

$email_settings=array('fotolia_id','fotolia_contributor','fotolia_query','fotolia_category','fotolia_account','fotolia_show');

for($i=0;$i<count($email_settings);$i++)
{
	$sql="update settings set svalue='".result($_POST[$email_settings[$i]])."' where setting_key='".$email_settings[$i]."'";
	$db->execute($sql);
}

$sql="update settings set svalue='" . (int)@$_POST[ 'fotolia_api' ] . "' where setting_key='fotolia_api'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'fotolia_pages' ] . "' where setting_key='fotolia_pages'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'fotolia_prints' ] . "' where setting_key='fotolia_prints'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'fotolia_files' ] . "' where setting_key='fotolia_files'";
$db->execute($sql);

$db->close();

header("location:index.php?d=3");
?>
