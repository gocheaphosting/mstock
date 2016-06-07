<?
if(!defined("site_root")){exit();}

$flag_empty=false;
$search_content="";

if($flow==1)
{
	$search_cache_id="f|".$sql_cache_id."|".$lang_symbol[$lng];
}
elseif($flow==2)
{
	$search_cache_id="f2|".$sql_cache_id."|".$lang_symbol[$lng];
}
else
{
	$search_cache_id="l|".$sql_cache_id."|".$lang_symbol[$lng];
}

if(!$smarty->isCached('listing.tpl',$search_cache_id) or $site_cache_catalog<0 or isset($_REQUEST["lightbox"]) or isset($_SESSION["cprotected"]))
{
	$sql.=" ".$com.$lm;
	//echo($sql."<br><br>");
	$rs->open($sql);
	if(!$rs->eof)
	{
		//Items for this category
		
		if($flow==1)
		{
			$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."item_list_flow.tpl");
		}
		elseif($flow==2)
		{
			if(file_exists($DOCUMENT_ROOT."/".$site_template_url."item_list_flow2.tpl"))
			{
				$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."item_list_flow2.tpl");
			}
			else
			{
				$boxcontent='<a href="{ITEM_URL}"><img src="{ITEM_IMG2}" alt="{ITEM_TITLE_FULL}" class="home_preview"  {WIDTH} {HEIGHT} style="{WIDTH2};{HEIGHT2}" {ITEM_LIGHTBOX}></a>';
			}
		}
		else
		{
			$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."item_list.tpl");
		}
		
		while(!$rs->eof)
		{
			if($rs->row["tbl"]=='photos')
			{
				$sql="select id_parent,title,data,published,description,viewed,keywords,rating as arating,downloaded,free,author,server1,category2,category3,url,rights_managed,featured from photos where id_parent=".$rs->row["id_parent"];
			}
			if($rs->row["tbl"]=='videos')
			{
				$sql="select id_parent,title,data,published,description,viewed,keywords,rating,downloaded,free,author,server1,category2,category3,url,rights_managed,featured from videos where id_parent=".$rs->row["id_parent"];
			}
			if($rs->row["tbl"]=='audio')
			{
				$sql="select id_parent,title,data,published,description,viewed,keywords,rating,downloaded,free,author,server1,category2,category3,url,rights_managed,featured from audio where id_parent=".$rs->row["id_parent"];
			}
			if($rs->row["tbl"]=='vector')
			{
				$sql="select id_parent,title,data,published,description,viewed,keywords,rating,downloaded,free,author,server1,category2,category3,url,rights_managed,featured from vector where id_parent=".$rs->row["id_parent"];
			}
			$dd->open($sql);
			if(!$dd->eof)
			{		
				$itembox=$boxcontent;
				
				$translate_results=translate_publication($rs->row["id_parent"],$dd->row["title"],$dd->row["description"],$dd->row["keywords"]);
		
				//Define author
				$user_name=show_user_name($dd->row["author"]);

				//Define url
				
				$item_url=item_url($rs->row["id_parent"],$dd->row["url"]);
				$itembox=str_replace("{ITEM_URL}",$item_url,$itembox);
				$itembox=str_replace("{ITEM_ID}",$rs->row["id_parent"],$itembox);

				//Define preview
				$item_img="";
				$item_lightbox="";

				//Show photo
				if($rs->row["tbl"]=='photos')
				{
					$item_type="photo";
					$itembox=str_replace("{CLASS}","1",$itembox);				
					$itembox=str_replace("{CLASS2}","fa-photo",$itembox);		
				}
			
				//Show video
				if($rs->row["tbl"]=='videos')
				{
					$item_type="video";
					$itembox=str_replace("{CLASS}","2",$itembox);
					$itembox=str_replace("{CLASS2}","fa-video-camera",$itembox);
				}
			
				//Show audio
				if($rs->row["tbl"]=='audio')
				{
					$item_type="audio";
					$itembox=str_replace("{CLASS}","3",$itembox);
					$itembox=str_replace("{CLASS2}","fa-music",$itembox);
				}
			
				//Show vector
				if($rs->row["tbl"]=='vector')
				{
					$item_type="vector";
					$itembox=str_replace("{CLASS}","4",$itembox);
					$itembox=str_replace("{CLASS2}","fa-cubes",$itembox);
				}
			
				$item_img=show_preview($rs->row["id_parent"],$item_type,1,1,$dd->row["server1"],$rs->row["id_parent"]);
			
				$hoverbox_results=get_hoverbox($rs->row["id_parent"],$item_type,$dd->row["server1"],$translate_results["title"],$user_name);			
				$itembox=str_replace("{ITEM_IMG2}",$hoverbox_results["flow_image"],$itembox);
				$itembox=str_replace("{ITEM_LIGHTBOX}",$hoverbox_results["hover"],$itembox);
					
				if($flow==1 or $flow==2)
				{	
					$str_width=" width='".$hoverbox_results["flow_width"]."' ";
					$str_height=" height='".$hoverbox_results["flow_height"]."' ";
			
					$str_width2="width:".$hoverbox_results["flow_width"]."px";
					$str_height2="height:".$hoverbox_results["flow_height"]."px";
				}
				
				if($flow==0)
				{	
					$str_width=" width='".$hoverbox_results["width"]."' ";
					$str_height=" height='".$hoverbox_results["height"]."' ";
			
					$str_width2="width:".$hoverbox_results["width"]."px";
					$str_height2="height:".$hoverbox_results["height"]."px";
				}

				$itembox=str_replace("{WIDTH}",$str_width,$itembox);
				$itembox=str_replace("{HEIGHT}",$str_height,$itembox);

				$itembox=str_replace("{WIDTH2}",$str_width2,$itembox);
				$itembox=str_replace("{HEIGHT2}",$str_height2,$itembox);

				$itembox=str_replace("{ADD_TO_CART}",word_lang("add to cart"),$itembox);
				$itembox=str_replace("{CART_FUNCTION}","add",$itembox);
				$itembox=str_replace("{CART_CLASS}","",$itembox);
		
				$acart = array();
				preg_match_all('|\{if cart\}(.*)\{/if\}|Uis', $itembox, $acart);
				if($dd->row["free"]!=1 and $dd->row["rights_managed"]==0 and isset($acart[1][0]) and isset($acart[0][0]))
				{
					$itembox=preg_replace('|\{if cart\}(.*)\{/if\}|Uis',$acart[1][0],$itembox);
				}
				else
				{
					if($dd->row["free"]==1)
					{
						$sql="select id from items where id_parent=".$rs->row["id_parent"]." and shipped<>1 order by priority desc";
						$dn->open($sql);
						if(!$dn->eof)
						{
							$itembox=preg_replace('|\{if cart\}(.*)\{/if\}|Uis',"<a href='".site_root."/members/count.php?id=".$dn->row["id"]."&id_parent=".$rs->row["id_parent"]."&type=".$item_type."&server=".$dd->row["server1"]."' class='ac'>".word_lang("free download")."</a>",$itembox);
						}
						else
						{
							$itembox=preg_replace('|\{if cart\}(.*)\{/if\}|Uis',"<span class='ac_text'>".word_lang("free download")."</span>",$itembox);
						}
					}
					if($dd->row["rights_managed"]!=0)
					{
						$itembox=preg_replace('|\{if cart\}(.*)\{/if\}|Uis',"<a href='".$item_url."' class='ac'>".word_lang("add to cart")."</a>",$itembox);
					}
				}
			
				$acartflow = array();
				preg_match_all('|\{if cartflow\}(.*)\{/if\}|Uis', $itembox, $acartflow);
				if($dd->row["free"]!=1 and  isset($acartflow[1][0]) and isset($acartflow[0][0]))
				{
					$itembox=preg_replace('|\{if cartflow\}(.*)\{/if\}|Uis',$acartflow[1][0],$itembox);
				}
				else
				{
					if($dd->row["free"]==1)
					{
						$sql="select id from items where id_parent=".$rs->row["id_parent"]." and shipped<>1 order by priority desc";
						$dn->open($sql);
						if(!$dn->eof)
						{
							$itembox=preg_replace('|\{if cartflow\}(.*)\{/if\}|Uis',"<li id='hb_free".$rs->row["id_parent"]."' class='hb_free' title='{lang.Free download}' onClick=\"location.href='".site_root."/members/count.php?id=".$dn->row["id"]."&id_parent=".$rs->row["id_parent"]."&type=".$item_type."&server=".$dd->row["server1"]."'\"></li>",$itembox);
						}
						else
						{
							$itembox=preg_replace('|\{if cartflow\}(.*)\{/if\}|Uis',"<li id='hb_free".$rs->row["id_parent"]."' class='hb_free' title='{lang.Free download}'></li>",$itembox);
						}
					}
				}
				
				
				$acartflow2 = array();
				preg_match_all('|\{if cartflow2\}(.*)\{/if\}|Uis', $itembox, $acartflow2);
				if($dd->row["free"]!=1 and  isset($acartflow2[1][0]) and isset($acartflow2[0][0]))
				{
					$itembox=preg_replace('|\{if cartflow2\}(.*)\{/if\}|Uis',$acartflow2[1][0],$itembox);
				}
				else
				{
					if($dd->row["free"]==1)
					{
						$sql="select id from items where id_parent=".$rs->row["id_parent"]." and shipped<>1 order by priority desc";
						$dn->open($sql);
						if(!$dn->eof)
						{
							$itembox=preg_replace('|\{if cartflow2\}(.*)\{/if\}|Uis',"<a href='".site_root."/members/count.php?id=".$dn->row["id"]."&id_parent=".$rs->row["id_parent"]."&type=".$item_type."&server=".$dd->row["server1"]."' class='btn btn-primary btn-free' title='{lang.Free download}'> <span class='add2cart'><i class='glyphicon glyphicon-download'> </i> {lang.Free download}</span> </a>",$itembox);
						}
						else
						{
							$itembox=preg_replace('|\{if cartflow2\}(.*)\{/if\}|Uis',"<a class='btn btn-primary' title='{lang.Free download}'> <span class='add2cart'><i class='glyphicon glyphicon-download'> </i> {lang.Free download}</span> </a>",$itembox);
						}
					}
				}
				
				$itembox=format_layout($itembox,"featured",$dd->row["featured"]);
				
				if($dd->row["data"]+3600*24*7>mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")))
				{
					$flag_new=true;
				}
				else
				{
					$flag_new=false;
				}
				
				$itembox=format_layout($itembox,"new",$flag_new);
				

				//Properties
				
				$itembox=str_replace("{ITEM_PUBLISHED}",date(date_short,$dd->row["data"]),$itembox);
				$itembox=str_replace("{ITEM_VIEWED}",$dd->row["viewed"],$itembox);
				$downloads=$dd->row["downloaded"];
				$itembox=str_replace("{DOWNLOADS}",strval($downloads),$itembox);
				$itembox=str_replace("{ITEM_IMG}",$item_img,$itembox);
				$itembox=str_replace("{ITEM_TITLE}","#".$rs->row["id_parent"],$itembox);
				$itembox=str_replace("{ITEM_TITLE_FULL}",$translate_results["title"],$itembox);
				$itembox=str_replace("{ITEM_DESCRIPTION}",str_replace("\r","<br>",str_replace("\n","<br>",$translate_results["description"])),$itembox);
				$itembox=str_replace("{ITEM_KEYWORDS}",$translate_results["keywords"],$itembox);	
				$itembox=str_replace("{TEMPLATE_ROOT}",site_root."/".$site_template_url,$itembox);
			
				$itembox=str_replace("{SITE_ROOT}",site_root,$itembox);
			
				$itembox=translate_text($itembox);
			
				//Save result
				$search_content.=$itembox;
			}
			
			$n++;
			$rs->movenext();
		}
		
		$boxcontent="";
	}
	else
	{
		$search_content.="<p><b>".word_lang("not found")."</b></p>";
		$flag_empty=true;
	}
}

//Smarty cache
if($site_cache_catalog>=0)
{
	if($site_cache_catalog>0)
	{
		$smarty->cache_lifetime = $site_cache_catalog*3600;
	}
	
	if(!isset($_REQUEST["lightbox"])  and  !isset($_SESSION["cprotected"]))
	{
		$smarty->assign('listing',$search_content);
		$search_content=$smarty->fetch('listing.tpl',$search_cache_id);
	}
}

?>