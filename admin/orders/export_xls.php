<? include("../function/db.php");?><?
//Check access
admin_panel_access("orders_orders");

include("../function/upload.php");


	
	

$xls_content="";

function xlsBOF() {
global $xls_content;
$xls_content.= pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
return;
}

function xlsEOF() {
global $xls_content;
$xls_content.= pack("ss", 0x0A, 0x00);
return;
}

function xlsWriteNumber($Row, $Col, $Value) {
global $xls_content;
$xls_content.= pack("sssss", 0x203, 14, $Row, $Col, 0x0);
$xls_content.= pack("d", $Value);
return;
}

function xlsWriteLabel($Row, $Col, $Value ) {
global $xls_content;
$L = strlen($Value);
$xls_content.= pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
$xls_content.= $Value;
return;
}



$export_file = "orders.xls";
header('Pragma: public');
header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header ("Pragma: no-cache");
header("Expires: 0");
header('Content-Transfer-Encoding: utf8');
header('Content-Type: application/vnd.ms-excel;'); 
header('Content-Disposition: attachment; filename="'.$export_file.'"'); 


xlsBOF(); 

xlsWriteLabel(0,0,"ID");
xlsWriteLabel(0,1,"User");
xlsWriteLabel(0,2,"Total");
xlsWriteLabel(0,3,"Status");
xlsWriteLabel(0,4,"Date");
xlsWriteLabel(0,5,"Subtotal");
xlsWriteLabel(0,6,"Discount");
xlsWriteLabel(0,7,"Shipping");
xlsWriteLabel(0,8,"Taxes");
xlsWriteLabel(0,9,"Shipping firstname");
xlsWriteLabel(0,10,"Shipping lastname");
xlsWriteLabel(0,11,"Shipping address");
xlsWriteLabel(0,12,"Shipping country");
xlsWriteLabel(0,13,"Shipping city");
xlsWriteLabel(0,14,"Shipping zip");
xlsWriteLabel(0,15,"Shipped");
xlsWriteLabel(0,16,"Billing firstname");
xlsWriteLabel(0,17,"Billing lastname");
xlsWriteLabel(0,18,"Billing address");
xlsWriteLabel(0,19,"Billing country");
xlsWriteLabel(0,20,"Billing city");
xlsWriteLabel(0,21,"Billing zip");

$n=1;
$sql="select id,user,total,status,data,subtotal,discount,shipping,tax,shipping_firstname,shipping_lastname,shipping_address,shipping_country,shipping_city,shipping_zip,shipped,billing_firstname,billing_lastname,billing_address,billing_country,billing_city,billing_zip from orders order by data desc";
$rs->open($sql);
while(!$rs->eof)
{
	xlsWriteLabel($n,0,$rs->row["id"]);
	xlsWriteLabel($n,1,user_url_back($rs->row["user"]));
	xlsWriteLabel($n,2,float_opt($rs->row["total"],2));
	if($rs->row["status"]==0)
	{
		xlsWriteLabel($n,3,"Pending");
	}
	else
	{
		xlsWriteLabel($n,3,"Approved");
	}
	xlsWriteLabel($n,4,date(date_format,$rs->row["data"]));
	xlsWriteLabel($n,5,float_opt($rs->row["subtotal"],2));
	xlsWriteLabel($n,6,float_opt($rs->row["discount"],2));
	xlsWriteLabel($n,7,float_opt($rs->row["shipping"],2));
	xlsWriteLabel($n,8,float_opt($rs->row["tax"],2));
	xlsWriteLabel($n,9,$rs->row["shipping_firstname"]);
	xlsWriteLabel($n,10,$rs->row["shipping_lastname"]);
	xlsWriteLabel($n,11,$rs->row["shipping_address"]);
	xlsWriteLabel($n,12,$rs->row["shipping_country"]);
	xlsWriteLabel($n,13,$rs->row["shipping_city"]);
	xlsWriteLabel($n,14,$rs->row["shipping_zip"]);
	if($rs->row["shipping"]*1!=0)
	{
		if($rs->row["shipped"]==1)
		{
			xlsWriteLabel($n,15,"Shipped");
		}
		else
		{
			xlsWriteLabel($n,15,"Not Shipped");
		}
	}
	else
	{
		xlsWriteLabel($n,15,"Digital");
	}
	xlsWriteLabel($n,16,$rs->row["billing_firstname"]);
	xlsWriteLabel($n,17,$rs->row["billing_lastname"]);
	xlsWriteLabel($n,18,$rs->row["billing_address"]);
	xlsWriteLabel($n,19,$rs->row["billing_country"]);
	xlsWriteLabel($n,20,$rs->row["billing_city"]);
	xlsWriteLabel($n,21,$rs->row["billing_zip"]);

	$n++;
	$rs->movenext();
}

xlsEOF();
echo($xls_content);

$db->close();
?>
