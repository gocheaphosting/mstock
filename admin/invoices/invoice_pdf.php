<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_invoices");

$invoice_content = '';
include("invoice_content.php");



define('_MPDF_URI','../plugins/mpdf/'); 
define('_MPDF_PATH', '../plugins/mpdf/');


    require_once $_SERVER["DOCUMENT_ROOT"].site_root.'/admin/plugins/mpdf/mpdf.php';


    $mpdf = new mPDF('utf-8', 'A4', '0', '', 5, 5, 5, 0, 0, 0);


   $stylesheet = "body,p,div,td{font:14px Arial}";
   


    $mpdf->CSSselectMedia='pdf';
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->list_indent_first_level = 1;

    $mpdf->WriteHTML($invoice_content,2);

    $mpdf->Output('invoice-'.(int)@$_GET["id"].'.pdf','I');










?>


