<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_bulkupload");

ob_clean();
header("Content-Type:image/jpeg");
ob_end_flush();

$img_sourse=$DOCUMENT_ROOT."/admin/storage/test.jpg";
$img_sourse2=$DOCUMENT_ROOT."/content/test.jpg";
$img_watermark=$DOCUMENT_ROOT."/content/watermark.png";
if(file_exists($img_sourse) and file_exists($img_watermark))
{
	copy($img_sourse,$img_sourse2);
	watermark($img_sourse2,$img_watermark);
	@readfile($img_sourse2);
}
$db->close();
?>