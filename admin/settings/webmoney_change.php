<? include("../function/db.php");?>
<?include("../../members/payments_settings.php");?>
<?
//Check access
admin_panel_access("settings_payments");



$activ=0;
if(isset($_POST["enable"])){$activ=1;}

$ipn=0;
if(isset($_POST["ipn"])){$ipn=1;}

$sql="update gateway_webmoney set account='".result($_POST["account"])."',ecode='".result($_POST["ecode"])."',activ=".$activ.",ipn=".$ipn;
$db->execute($sql);

$db->close();

header("location:payments.php");
?>