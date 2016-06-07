<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("affiliates_settings");

$sql="update settings set svalue=".(float)$_POST["buyer"]." where setting_key='buyer_commission'";
$db->execute($sql);

$sql="update settings set svalue=".(float)$_POST["seller"]." where setting_key='seller_commission'";
$db->execute($sql);

if($_POST["addto"]==1)
{
	$sql="update users set aff_commission_buyer=".(float)$_POST["buyer"].",aff_commission_seller=".(float)$_POST["seller"]." where utype='affiliate' or utype='common'";
	$db->execute($sql);
}

$db->close();

header("location:settings.php");
?>