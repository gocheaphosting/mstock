<? include("../function/db.php");?>
<?include("../../members/payments_settings.php");?>
<?
//Check access
admin_panel_access("settings_payments");



$activ=0;
if(isset($_POST["enable"])){$activ=1;}

$test=0;
if(isset($_POST["test"])){$test=1;}


$sql="update gateway_dwolla set account='".result($_POST["account"])."',apikey='".result($_POST["apikey"])."',apisecret='".result($_POST["apisecret"])."',pin='".result($_POST["pin"])."',activ=".$activ.",test=".$test;
$db->execute($sql);

$db->close();

header("location:payments.php");
?>