<?
if(!defined("site_root")){exit();}
$box_photographers="";


if (!$smarty->isCached('photographers.tpl',"photographers".$site_template_id))
{

	$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."box_photographers.tpl");



	//Define featured categories
	$catlist="";
	$sql="select name,menu from user_category where menu=1";
	$rs->open($sql);
	while(!$rs->eof)
	{
		if($catlist!=""){$catlist.=" or ";}
		$catlist.=" category='".$rs->row["name"]."' ";
		$rs->movenext();
	}
	
	if($catlist!="")
	{
		$catlist="(".$catlist.")";
	}


	//List of featured photographers
	$sql="select id_parent,login,name,lastname,category,data1 from users where ".$catlist." and (utype='seller' or utype='common') order by data1";
	$rs->open($sql);
	while(!$rs->eof)
	{
		$url=site_root."/users/".$rs->row["id_parent"].".html";

		$qty=0;

		$sql="select id_parent from photos where userid=".$rs->row["id_parent"]." or author='".$rs->row["login"]."'";
		$ds->open($sql);
		$qty+=$ds->rc;

		$sql="select id_parent from videos where userid=".$rs->row["id_parent"]." or author='".$rs->row["login"]."'";
		$ds->open($sql);
		$qty+=$ds->rc;

		$sql="select id_parent from audio where userid=".$rs->row["id_parent"]." or author='".$rs->row["login"]."'";
		$ds->open($sql);
		$qty+=$ds->rc;

		$sql="select id_parent from vector where userid=".$rs->row["id_parent"]." or author='".$rs->row["login"]."'";
		$ds->open($sql);
		$qty+=$ds->rc;

		$box_photographers.=str_replace("{ITEM_QUANTITY}",strval($qty),str_replace("{ITEM_URL}",$url,str_replace("{ITEM_TITLE}",$rs->row["name"]." ".$rs->row["lastname"],$boxcontent)));


		$rs->movenext();
	}

}
$smarty->cache_lifetime = 3600;
$smarty->assign("photographers",$box_photographers);
$box_photographers=$smarty->fetch('photographers.tpl',"photographers".$site_template_id);



$file_template=str_replace("{BOX_PHOTOGRAPHERS}",$box_photographers,$file_template);
?>