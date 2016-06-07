<? include("../function/db.php");?>
<?include("../../members/payments_settings.php");?>
<?
//Check access
admin_panel_access("settings_payments");



$activ=0;
if(isset($_POST["enable"])){$activ=1;}




$sql="update gateway_webpay set account='".result($_POST["account"])."',password='".result($_POST["password"])."',activ=".$activ.",test=".(int)@$_POST["test"].",login='".result($_POST["login"])."',password2='".result($_POST["password2"])."'";
$db->execute($sql);

$db->close();

header("location:payments.php");
?>