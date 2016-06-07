<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_invoices");

$sql="update invoices set invoice_number=".(int)@$_POST["invoice_number"].",comments='".result($_POST["comments"])."' where id=".(int)@$_GET["id"];
$db->execute($sql);

header("location:invoice.php?id=".(int)@$_POST["invoice_number"]);
?>
