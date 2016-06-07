<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_invoices");

$email_settings=array('invoice_prefix','invoice_number','credit_notes_prefix','credit_notes_number','company_name','company_address1','company_address2','company_country','company_vat_number');

for($i=0;$i<count($email_settings);$i++)
{
	$sql="update settings set svalue='".result($_POST[$email_settings[$i]])."' where setting_key='".$email_settings[$i]."'";
	$db->execute($sql);
}

$sql="update settings set svalue='" . (int)@$_POST[ 'invoice_publish' ] . "' where setting_key='invoice_publish'";
$db->execute($sql);

$db->close();

header("location:index.php");
?>
