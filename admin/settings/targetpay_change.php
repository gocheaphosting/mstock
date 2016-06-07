<? include("../function/db.php");?>
<?include("../../members/payments_settings.php");?>
<?
//Check access
admin_panel_access("settings_payments");



$sql="update settings set svalue='".result($_POST["targetpay_account"])."' where stype='gateways' and setting_key='targetpay_account'";
$db->execute($sql);
		
$sql="update settings set svalue='".(int)@$_POST["targetpay_test"]."' where stype='gateways' and setting_key='targetpay_test'";
$db->execute($sql);
		
$sql="update settings set svalue='".(int)@$_POST["targetpay_active"]."' where stype='gateways' and setting_key='targetpay_active'";
$db->execute($sql);

$db->close();

header("location:payments.php");
?>