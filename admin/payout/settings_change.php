<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_payout");



$sql="update settings set svalue='".(float)$_POST["payout_limit"]."' where setting_key='payout_limit'";
$db->execute($sql);

$sql="update settings set svalue='".(int)@$_POST["payout_set"]."' where setting_key='payout_set'";
$db->execute($sql);

if($_POST["payout_action"] == 1)
{
	$sql="update users set payout_limit='".(float)$_POST["payout_limit"]."'";
	$db->execute($sql);	
}

$db->close();

header("location:index.php?d=1");
?>