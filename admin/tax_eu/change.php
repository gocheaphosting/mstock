<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_taxeseu");

$sql="update settings set svalue='" . (int)@$_POST[ 'eu_tax_active' ] . "' where setting_key='eu_tax'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'eu_tax_b2b' ] . "' where setting_key='eu_tax_b2b'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'eu_tax_commission' ] . "' where setting_key='eu_tax_commission'";
$db->execute($sql);

$sql="update settings set svalue='" . (int)@$_POST[ 'eu_tax_payout' ] . "' where setting_key='eu_tax_payout'";
$db->execute($sql);

$db->close();

header("location:index.php");
?>
