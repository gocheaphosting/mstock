<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_bulkupload");

ob_clean();
header("Content-Type:image/jpeg");
ob_end_flush();

$img_sourse=$DOCUMENT_ROOT.$global_settings["photopreupload"].result_file($_GET["file"]);

$size = GetImageSize($img_sourse); 
$im_in = ImageCreateFromJPEG($img_sourse); 

$width=250;

if($size[0]>$size[1])
{
$new_height = ($width * $size[1]) / $size[0]; // Generate new height for image
}
else
{
$new_height=$width;
$width=($width * $size[0]) / $size[1]; 
}
$im_out = imagecreatetruecolor($width, $new_height); 

imagecopyresized($im_out, $im_in, 0, 0, 0, 0, $width, $new_height, $size[0], $size[1]); 

ImageJPEG($im_out); 
ImageDestroy($im_in); 
ImageDestroy($im_out); 


$db->close();
?>