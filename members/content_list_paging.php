<?
include("../admin/function/db.php");
include("JsHttpRequest.php");

$JsHttpRequest =new JsHttpRequest($mtg);
$id_parent=(int)$_REQUEST["id_parent"];

include("content_list_vars.php");

if($stock == 'site')
{
	include("content_list_items.php");
}

if($stock == 'istockphoto')
{
	include("content_list_istockphoto.php");
}	

if($stock == 'shutterstock')
{
	include("content_list_shutterstock.php");
}	

if($stock == 'fotolia')
{
	include("content_list_fotolia.php");
}	

if($stock == 'depositphotos')
{
	include("content_list_depositphotos.php");
}	

if($stock == 'rf123')
{
	include("content_list_123rf.php");
}	

if($stock == 'bigstockphoto')
{
	include("content_list_bigstockphoto.php");
}	
	
//Show result
if(!$flag_empty and $search_content != word_lang("not found") and $search_content != "<p><b>".word_lang("not found")."</b></p>")
{
	echo($search_content);
}
?>