<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

$id=(int)$_REQUEST["id"];



if(isset($_SESSION["people_id"]))
{
	$lightbox=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."lightbox.tpl");
	
	
	//Show images
	$size_result=define_thumb_size($id);
	$lightbox=str_replace("{IMAGE}",$size_result["thumb"],$lightbox);
	$lightbox=str_replace("{WIDTH}",$size_result["width"],$lightbox);
	$lightbox=str_replace("{HEIGHT}",$size_result["height"],$lightbox);
	//End. Show images
	
	//Show select
	$lightbox_list="";
	$sql="select id_parent from lightboxes_admin where user=".(int)$_SESSION["people_id"];
	$rs->open($sql);
	while(!$rs->eof)
	{
		$sql="select title from lightboxes where id=".$rs->row["id_parent"]." order by title";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$sel="";
			$sql="select id_parent from lightboxes_files where item=".$id." and id_parent=".$rs->row["id_parent"];
			$dr->open($sql);
			if(!$dr->eof)
			{
				$sel="checked";
			}
			
			$lightbox_list.="<div style='margin-top:5px'><input type='checkbox' id='chk".$rs->row["id_parent"]."' name='chk".$rs->row["id_parent"]."' ".$sel.">&nbsp;".$ds->row["title"]."</div>";
		}
		$rs->movenext();
	}
	$lightbox_list.="<div style='margin-top:5px'><input type='checkbox' id='new_lightbox' name='new_lightbox'>&nbsp;<input type='text' value='".word_lang("new")."' id='new' name='new' style='width:100px' onClick=\"this.value=''\"></div>";

	$lightbox=str_replace("{LIGHTBOXES}",$lightbox_list,$lightbox);
	//End. Show select
	
	$lightbox=str_replace("{ID}",strval($id),$lightbox);
	$lightbox=str_replace("{SITE_ROOT}",site_root,$lightbox);
	
	$lightbox=translate_text($lightbox);
	
	$GLOBALS['_RESULT'] = array(
 	"authorization"     => 1,
  	"lightbox_message"     => $lightbox
	);
}
else
{
	$GLOBALS['_RESULT'] = array(
 	"authorization"     => 0,
  	"lightbox_message"     => word_lang("You should login to use the option")
	);
}

$db->close();
?>