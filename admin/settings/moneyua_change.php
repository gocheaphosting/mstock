<? include("../function/db.php");?>
<?include("../../members/payments_settings.php");?>
<?
//Check access
admin_panel_access("settings_payments");



$activ=0;
if(isset($_POST["enable"])){$activ=1;}

$testmode=0;
if(isset($_POST["testmode"])){$testmode=1;}


$sql="update gateway_moneyua set account='".result($_POST["account"])."',password='".result($_POST["password"])."',activ=".$activ.",testmode=".$testmode.",commission=".(int)$_POST["commission"];
$db->execute($sql);

$db->close();

header("location:payments.php");
?>