<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_invoices");

if(file_exists($DOCUMENT_ROOT."/content/invoice_logo.jpg"))
{
	unlink($DOCUMENT_ROOT."/content/invoice_logo.jpg");
}

$db->close();

header("location:index.php");
?>
