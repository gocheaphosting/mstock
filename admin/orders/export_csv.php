<? include("../function/db.php");?><?
//Check access
admin_panel_access("orders_orders");

include("../function/upload.php");


	
	




$export_file = "orders.csv";
header('Pragma: public');
header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header ("Pragma: no-cache");
header("Expires: 0");
header('Content-Transfer-Encoding: utf8');
header('Content-type: application/csv;'); 
header('Content-Disposition: attachment; filename="'.$export_file.'"'); 



echo("ID;");
echo("User;");
echo("Total;");
echo("Status;");
echo("Date;");
echo("Subtotal;");
echo("Discount;");
echo("Shipping;");
echo("Taxes;");
echo("Shipping firstname;");
echo("Shipping lastname;");
echo("Shipping address;");
echo("Shipping country;");
echo("Shipping city;");
echo("Shipping zip;");
echo("Shipped;");
echo("Billing firstname;");
echo("Billing lastname;");
echo("Billing address;");
echo("Billing country;");
echo("Billing city;");
echo("Billing zip");
echo("\n");

$sql="select id,user,total,status,data,subtotal,discount,shipping,tax,shipping_firstname,shipping_lastname,shipping_address,shipping_country,shipping_city,shipping_zip,shipped,billing_firstname,billing_lastname,billing_address,billing_country,billing_city,billing_zip from orders order by data desc";
$rs->open($sql);
while(!$rs->eof)
{
	echo($rs->row["id"].";");
	echo(user_url_back($rs->row["user"]).";");
	echo(float_opt($rs->row["total"],2).";");
	if($rs->row["status"]==0)
	{
		echo("Pending;");
	}
	else
	{
		echo("Approved;");
	}
	echo(date(date_format,$rs->row["data"]).";");
	echo(float_opt($rs->row["subtotal"],2).";");
	echo(float_opt($rs->row["discount"],2).";");
	echo(float_opt($rs->row["shipping"],2).";");
	echo(float_opt($rs->row["tax"],2).";");
	echo($rs->row["shipping_firstname"].";");
	echo($rs->row["shipping_lastname"].";");
	echo($rs->row["shipping_address"].";");
	echo($rs->row["shipping_country"].";");
	echo($rs->row["shipping_city"].";");
	echo($rs->row["shipping_zip"].";");
	if($rs->row["shipping"]*1!=0)
	{
		if($rs->row["shipped"]==1)
		{
			echo("Shipped;");
		}
		else
		{
			echo("Not Shipped;");
		}
	}
	else
	{
		echo("Digital;");
	}
	echo($rs->row["billing_firstname"].";");
	echo($rs->row["billing_lastname"].";");
	echo($rs->row["billing_address"].";");
	echo($rs->row["billing_country"].";");
	echo($rs->row["billing_city"].";");
	echo($rs->row["billing_zip"].";");

	echo("\n");
	$rs->movenext();
}

$db->close();
?>
