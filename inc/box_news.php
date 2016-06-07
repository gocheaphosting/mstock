<?
if(!defined("site_root")){exit();}

$box_news="";
$smarty_news_id="news|".$lng."|".$site_template_id;

if (!$smarty->isCached('news.tpl',$smarty_news_id))
{
	$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."box_news.tpl");

	$sql="select * from news order by data desc limit ".(int)$global_settings["qnews"];
	$rs->open($sql);
	while(!$rs->eof)
	{
		$box_news.=str_replace("{NEWS_ANNOUNCE}",$rs->row['announce'],str_replace("{NEWS_DATE}",date(date_short,$rs->row['data']),str_replace("{NEWS_URL}","{SITE_ROOT}news/".$rs->row['id_parent']."/",str_replace("{NEWS_TITLE}",$rs->row["title"],$boxcontent))));
		$rs->movenext();
	}
}

$smarty->cache_lifetime = -1;
$smarty->assign("news", $box_news);
$box_news=$smarty->fetch('news.tpl',$smarty_news_id);



$file_template=str_replace("{BOX_NEWS}",$box_news,$file_template);
?>