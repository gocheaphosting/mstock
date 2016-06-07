<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_invoices");


$sql="select * from invoices where invoice_number=" . (int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
	$invoice_number = $global_settings["credit_notes_number"] + 1;
	
	$sql="insert into invoices (invoice_number,order_id,order_type,status,comments,refund) values (".$invoice_number .",".$rs->row["order_id"].",'".$rs->row["order_type"]."',".$global_settings["invoice_publish"].",'".$rs->row["comments"]."',1)";
	$db->execute($sql);
	
	$sql="update settings set svalue=".$invoice_number." where setting_key='credit_notes_number'";
	$db->execute($sql);
}

header("location:../invoices/");
?>
