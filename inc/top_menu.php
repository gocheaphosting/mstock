<?
if(!defined("site_root")){exit();}
$topmenu="";

$topmenu_id="topmenu|".$lng."|".$site_template_id;

if (!$smarty->isCached('topmenu.tpl',$topmenu_id))
{


	$button=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."topmenu.tpl");
	$button2=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."topmenu2.tpl");
	$separator=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."topmenu_separator.tpl");

	$topmenu.="<div class='topmenu'>".$button."</div>";


	$sql="select a.id,a.id_parent,b.id_parent,b.title,b.url,b.priority from structure a, navigation b where a.id=b.id_parent and a.id_parent=4012 order by b.priority";
	$rs->open($sql);
	while(!$rs->eof)
	{
		$topmenu.="<div class='topmenu'>".$separator."</div><div class='topmenu'>".str_replace("{ITEM_TITLE}",word_lang($rs->row["title"]),str_replace("{ITEM_URL}",$rs->row["url"],$button2))."</div>";
		$rs->movenext();
	}
}

$smarty->cache_lifetime = -1;
$smarty->assign("topmenu", $topmenu);
$topmenu=$smarty->fetch('topmenu.tpl',$topmenu_id);




$file_template=str_replace("{TOPMENU}",$topmenu,$file_template);
?>