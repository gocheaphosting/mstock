<? include("../function/db.php");?>
<?include("../../members/payments_settings.php");?>
<?
//Check access
admin_panel_access("settings_payments");



$activ=0;
if(isset($_POST["enable"])){$activ=1;}

$ipn=0;
if(isset($_POST["ipn"])){$ipn=1;}

$sql="update gateway_2checkout set account='".result($_POST["account"])."',activ=".$activ.",ipn=".$ipn;
$db->execute($sql);


$db->close();

header("location:payments.php");
?>