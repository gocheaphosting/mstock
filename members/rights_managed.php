<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

include("../admin/rights_managed/rights_managed_functions.php");



$title=word_lang("rights managed");
$price=0;
$publication_title="";
$publication_id=(int)$_REQUEST["id"];





$module_table=0;

$sql="select name,module_table from structure where id=".$publication_id;
$dr->open($sql);
if(!$dr->eof)
{
	$translate_results=translate_publication($publication_id,$dr->row["name"],"","");
	$publication_title=$translate_results["title"];
	
	$module_table=$dr->row["module_table"];
}


if($module_table==30)
{
	$sql="select rights_managed from photos where id_parent=".$publication_id;
}

if($module_table==31)
{
	$sql="select rights_managed from videos where id_parent=".$publication_id;
}

if($module_table==52)
{
	$sql="select rights_managed from audio where id_parent=".$publication_id;
}

if($module_table==53)
{
	$sql="select rights_managed from vector where id_parent=".$publication_id;
}

$price_id=0;
$dr->open($sql);
if(!$dr->eof)
{
	$price_id=$dr->row["rights_managed"];
}



unset($_SESSION["rights_managed".$publication_id]);
unset($_SESSION["rights_managed_value".$publication_id]);
$_SESSION["rights_managed".$publication_id]=array();
$_SESSION["rights_managed_value".$publication_id]=array();


if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."rights_managed.tpl"))
{
	$cart_content=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."rights_managed.tpl");
}
else
{
	$cart_content="Error. There is no the template: /tremplates/template[n]/rights_managed.tpl. You should upload it on ftp.";
}


$sql="select price from rights_managed where id=".$price_id;
$rs->open($sql);
if(!$rs->eof)
{
	$price=$rs->row["price"];
}

$_SESSION["rights_managed_price".$publication_id]=$price;

//Show thumb
$size_result=define_thumb_size($publication_id);
$cart_content=str_replace("{IMAGE}",$size_result["thumb"],$cart_content);
$cart_content=str_replace("{WIDTH}",$size_result["width"],$cart_content);
$cart_content=str_replace("{HEIGHT}",$size_result["height"],$cart_content);

$cart_content=str_replace("{PUBLICATION_TITLE}",$publication_title,$cart_content);
$cart_content=str_replace("{TITLE}",$title,$cart_content);
$cart_content=str_replace("{ID}",$publication_id,$cart_content);
$cart_content=str_replace("{PRICE}",currency(1).float_opt($price,2)." ".currency(2),$cart_content);

$itg="";
$nlimit=0;
$flag_visible=true;
$first_step_id=0;
build_rights_managed($publication_id,0,$price_id);

$rights_managed=$itg;


$cart_content=str_replace("{RIGHTS_MANAGED}",$rights_managed,$cart_content);

$cart_content=str_replace("{SITE_ROOT}",site_root,$cart_content);
$cart_content=translate_text($cart_content);



$GLOBALS['_RESULT'] = array(
  "cart_content"     => $cart_content
); 

$db->close();
?>