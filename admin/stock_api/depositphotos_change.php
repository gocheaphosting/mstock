<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_stockapi");

$email_settings=array('depositphotos_id','depositphotos_affiliate','depositphotos_contributor','depositphotos_category','depositphotos_show');

for($i=0;$i<count($email_settings);$i++)
{
	$sql="update settings set svalue='".result($_POST[$email_settings[$i]])."' where setting_key='".$email_settings[$i]."'";
	$db->execute($sql);
}

$sql="update settings set svalue='" . (int)@$_POST[ 'depositphotos_api' ] . "' where setting_key='depositphotos_api'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'depositphotos_pages' ] . "' where setting_key='depositphotos_pages'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'depositphotos_prints' ] . "' where setting_key='depositphotos_prints'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'depositphotos_files' ] . "' where setting_key='depositphotos_files'";
$db->execute($sql);

$db->close();

header("location:index.php?d=4");
?>
