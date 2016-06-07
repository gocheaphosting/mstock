<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);


$id=(int)$_REQUEST["id"];


$prints_content=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."prints_preview.tpl");

$sql="select title,description from prints where id_parent=".$id;
$rs->open($sql);
if(!$rs->eof)
{
	$prints_content=str_replace("{TITLE}",$rs->row["title"],$prints_content);
	$prints_content=str_replace("{DESCRIPTION}",$rs->row["description"],$prints_content);
	
	$preview_items="";
	
	if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".$id."_1_big.jpg"))
	{
		$preview_items.="<img src='".site_root."/content/prints/product".$id."_1_big.jpg'>";
	}
	
	if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".$id."_2_big.jpg"))
	{
		$preview_items.="<img src='".site_root."/content/prints/product".$id."_2_big.jpg'>";
	}
	
	if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".$id."_3_big.jpg"))
	{
		$preview_items.="<img src='".site_root."/content/prints/product".$id."_3_big.jpg'>";
	}
	
	if($preview_items!="")
	{
		$preview_items="<div id='galleria'>".$preview_items."</div><script> Galleria.loadTheme('".site_root."/admin/plugins/galleria/themes/classic/galleria.classic.js'); Galleria.run('#galleria');</script>";		
	}
	
	$prints_content=str_replace("{IMAGE}",$preview_items,$prints_content);
	
}
$GLOBALS['_RESULT'] = array(
  "prints_content"     => $prints_content
); 

$db->close();
?>