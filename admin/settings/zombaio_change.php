<? include("../function/db.php");?>
<?include("../../members/payments_settings.php");?>
<?
//Check access
admin_panel_access("settings_payments");



$activ=0;
if(isset($_POST["enable"])){$activ=1;}


$sql="update gateway_zombaio set account='".result($_POST["account"])."',password='".result($_POST["password"])."',price='".result($_POST["price"])."',activ=".$activ;
$db->execute($sql);

$db->close();

header("location:payments.php");
?>