<? include("../function/db.php");?>
<?include("../../members/payments_settings.php");?>
<?
//Check access
admin_panel_access("settings_payments");



$sql="update settings set svalue='".result($_POST["payumoney_account"])."' where stype='gateways' and setting_key='payumoney_account'";
$db->execute($sql);

$sql="update settings set svalue='".result($_POST["payumoney_password"])."' where stype='gateways' and setting_key='payumoney_password'";
$db->execute($sql);

		
$sql="update settings set svalue='".(int)@$_POST["payumoney_active"]."' where stype='gateways' and setting_key='payumoney_active'";
$db->execute($sql);

$sql="update settings set svalue='".(int)@$_POST["payumoney_test"]."' where stype='gateways' and setting_key='payumoney_test'";
$db->execute($sql);

$db->close();

header("location:payments.php");
?>