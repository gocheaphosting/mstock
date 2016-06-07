<? include("../function/db.php");?>
<?include("../../members/payments_settings.php");?>
<?
//Check access
admin_panel_access("settings_payments");



$sql="update settings set svalue='".result($_POST["checkoutfi_account"])."' where stype='gateways' and setting_key='checkoutfi_account'";
$db->execute($sql);

$sql="update settings set svalue='".result($_POST["checkoutfi_password"])."' where stype='gateways' and setting_key='checkoutfi_password'";
$db->execute($sql);

		
$sql="update settings set svalue='".(int)@$_POST["checkoutfi_active"]."' where stype='gateways' and setting_key='checkoutfi_active'";
$db->execute($sql);



$db->close();

header("location:payments.php");
?>