<? include("../function/db.php");?>
<?include("../../members/payments_settings.php");?>
<?
//Check access
admin_panel_access("settings_payments");



$sql="update settings set svalue='".result($_POST["yandex_account"])."' where stype='gateways' and setting_key='yandex_account'";
$db->execute($sql);

$sql="update settings set svalue='".result($_POST["yandex_account2"])."' where stype='gateways' and setting_key='yandex_account2'";
$db->execute($sql);

$sql="update settings set svalue='".result($_POST["yandex_password"])."' where stype='gateways' and setting_key='yandex_password'";
$db->execute($sql);
		
$sql="update settings set svalue='".(int)@$_POST["yandex_test"]."' where stype='gateways' and setting_key='yandex_test'";
$db->execute($sql);
		
$sql="update settings set svalue='".(int)@$_POST["yandex_active"]."' where stype='gateways' and setting_key='yandex_active'";
$db->execute($sql);

$db->close();

header("location:payments.php");
?>