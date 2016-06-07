<? include("../function/db.php");?>
<?include("../../members/payments_settings.php");?>
<?
//Check access
admin_panel_access("settings_payments");



$sql="update settings set svalue='".result($_POST["verotel_account"])."' where stype='gateways' and setting_key='verotel_account'";
$db->execute($sql);

$sql="update settings set svalue='".result($_POST["verotel_password"])."' where stype='gateways' and setting_key='verotel_password'";
$db->execute($sql);

		
$sql="update settings set svalue='".(int)@$_POST["verotel_active"]."' where stype='gateways' and setting_key='verotel_active'";
$db->execute($sql);



$db->close();

header("location:payments.php");
?>