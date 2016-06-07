<?
include("../admin/function/db.php");
if(!isset($_SESSION["people_id"])){header("location:login.php");exit();}

$flag = false;


$sql="select order_id,order_type from invoices where invoice_number=".(int)@$_GET["id"]." and status=1";
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["order_type"]=="orders")
	{
		$sql="select id from orders where id=".$rs->row["order_id"]." and user=".(int)$_SESSION["people_id"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$flag = true;	
		}	
	}
	if($rs->row["order_type"]=="credits")
	{
		$sql="select id_parent from credits_list where id_parent=".$rs->row["order_id"]." and user='".result($_SESSION["people_login"])."'";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$flag = true;		
		}	
	}
	if($rs->row["order_type"]=="subscription")
	{
		$sql="select id_parent from subscription_list where id_parent=".$rs->row["order_id"]." and user='".result($_SESSION["people_login"])."'";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$flag = true;		
		}	
	}
}

if($flag == true)
{
	$invoice_content = '';
	include("../admin/invoices/invoice_content.php");
		
	define('_MPDF_URI','../admin/plugins/mpdf/'); 
	define('_MPDF_PATH', '../admin/plugins/mpdf/');
		
	require_once $_SERVER["DOCUMENT_ROOT"].site_root.'/admin/plugins/mpdf/mpdf.php';

	$mpdf = new mPDF('utf-8', 'A4', '0', '', 5, 5, 5, 0, 0, 0);

   	$stylesheet = "body,p,div,td{font:14px Arial}";
   
	$mpdf->CSSselectMedia='pdf';
	$mpdf->WriteHTML($stylesheet, 1);
	$mpdf->list_indent_first_level = 1;

	$mpdf->WriteHTML($invoice_content,2);

	$mpdf->Output('invoice-'.(int)@$_GET["id"].'.pdf','I');
}
?>


