<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

$id=@$_REQUEST['id'];

$smarty_share_id="share|".$id;
if(!$smarty->isCached('share.tpl',$smarty_share_id))
{
	$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."share.tpl");
	$boxcontent=str_replace("{SITE_ROOT}",site_root,$boxcontent);
	$boxcontent=str_replace("{WORD_SHARE}",word_lang("share this"),$boxcontent);

	$title="";
	$description="";
	$image="";

	$sql="select id_parent,title,description,url,server1 from photos where id_parent=".(int)$id;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$title=str_replace("\"","",str_replace(" ","+",$rs->row["title"]));
		$description=str_replace("\"","",str_replace(" ","+",$rs->row["description"]));
		$url=surl.item_url($id,$rs->row["url"]);
		$image=surl.show_preview($rs->row["id_parent"],"photo",2,1,$rs->row["server1"],$rs->row["id_parent"]);
	}

	$sql="select id_parent,title,description,url,server1 from videos where id_parent=".(int)$id;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$title=str_replace("\"","",str_replace(" ","+",$rs->row["title"]));
		$description=str_replace("\"","",str_replace(" ","+",$rs->row["description"]));
		$url=surl.item_url($id,$rs->row["url"]);
		$image=surl.show_preview($rs->row["id_parent"],"video",1,1,$rs->row["server1"],$rs->row["id_parent"]);
	}	

	$sql="select id_parent,title,description,url,server1 from audio where id_parent=".(int)$id;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$title=str_replace("\"","",str_replace(" ","+",$rs->row["title"]));
		$description=str_replace("\"","",str_replace(" ","+",$rs->row["description"]));
		$url=surl.item_url($id,$rs->row["url"]);
		$image=surl.show_preview($rs->row["id_parent"],"audio",1,1,$rs->row["server1"],$rs->row["id_parent"]);
	}

	$sql="select id_parent,title,description,url,server1 from vector where id_parent=".(int)$id;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$title=str_replace("\"","",str_replace(" ","+",$rs->row["title"]));
		$description=str_replace("\"","",str_replace(" ","+",$rs->row["description"]));
		$url=surl.item_url($id,$rs->row["url"]);
		$image=surl.show_preview($rs->row["id_parent"],"vector",2,1,$rs->row["server1"],$rs->row["id_parent"]);
	}

	$boxcontent=str_replace("{SHARE_TITLE}",$title,$boxcontent);
	$boxcontent=str_replace("{SHARE_URL}",$url,$boxcontent);
	$boxcontent=str_replace("{SHARE_IMAGE}",$image,$boxcontent);
	$boxcontent=str_replace("{SHARE_DESCRIPTION}",$description,$boxcontent);
}
$smarty->cache_lifetime = -1;
$smarty->assign("share", $boxcontent);
$boxcontent=$smarty->fetch('share.tpl',$smarty_share_id);

echo($boxcontent);

$db->close();
?>