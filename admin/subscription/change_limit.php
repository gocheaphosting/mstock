<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_subscription");

$sql="update subscription_limit set activ=0";
$db->execute($sql);

$sql="update subscription_limit set activ=1 where name='".result($_POST["limit"])."'";
$db->execute($sql);

$_SESSION["subscription_limit"]=result($_POST["limit"]);

$db->close();

header("location:index.php");
?>