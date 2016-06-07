<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_payout");



$sql="update settings set svalue='".(float)$_POST["price"]."' where setting_key='payout_price'";
$db->execute($sql);

$db->close();

header("location:index.php?d=2");
?>