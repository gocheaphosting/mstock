<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_invoices");

$mass_orders = array();

if($global_settings["credits"])
{
	$sql="select data,id_parent from credits_list where approved=1 and quantity>0 and total>0 order by data";
	$rs->open($sql);
	while(!$rs->eof)
	{
		$mass_orders[$rs->row["data"]] = "credits-".$rs->row["id_parent"];
		$rs->movenext();
	}
}
else
{
	$sql="select data,id from orders where status=1 order by data";
	$rs->open($sql);
	while(!$rs->eof)
	{
		$mass_orders[$rs->row["data"]] = "orders-".$rs->row["id"];
		$rs->movenext();
	}
}

if($global_settings["subscription"])
{
	$sql="select data1,id_parent from subscription_list where approved=1 and total>0 order by data1";
	$rs->open($sql);
	while(!$rs->eof)
	{
		$mass_orders[$rs->row["data1"]] = "subscription-".$rs->row["id_parent"];
		$rs->movenext();
	}
}

ksort($mass_orders);

var_dump($mass_orders);
$i=0;
foreach ($mass_orders as $key => $value) 
{
	$order_element = explode("-",$value);
	$order_id = $order_element[1];
	$order_type = $order_element[0];
	
	$sql="select id from invoices where order_id=".$order_id." and order_type='".$order_type."'";
	$rs->open($sql);
	if($rs->eof)
	{
		$i++;
		
		$invoice_number = $global_settings["invoice_number"] + $i;
		
		$sql="insert into invoices (invoice_number,order_id,order_type) values (".$invoice_number .",".$order_id.",'".$order_type."')";
		$db->execute($sql);
	}
	
	$sql="update settings set svalue=".$invoice_number." where setting_key='invoice_number'";
	$db->execute($sql);
}

$db->close();

header("location:index.php");
?>
