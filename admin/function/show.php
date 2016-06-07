<?
if(!defined("site_root")){exit();}




//The function shows a publication's category
function show_category($id,$category2,$category3)
{
global $db;
global $ds;
global $dr;
$category_list="";

	$sql="select id_parent from structure where id=".(int)$id;
	$ds->open($sql);
	if(!$ds->eof)
	{
		$sql="select title from category where id_parent=".$ds->row["id_parent"];
		$dr->open($sql);
		if(!$dr->eof)
		{
			$translate_results=translate_category($ds->row["id_parent"],$dr->row["title"],"","");
			
			$category_list.="<a href='".category_url($ds->row["id_parent"])."'>".$translate_results["title"]."</a>";
		}

		if($ds->row["id_parent"]!=$category2 and (int)$category2!=0)
		{
			$sql="select title from category where id_parent=".(int)$category2;
			$dr->open($sql);
			if(!$dr->eof)
			{
				$translate_results=translate_category((int)$category2,$dr->row["title"],"","");
				
				$category_list.=" <a href='".category_url($category2)."'>".$translate_results["title"]."</a>";
			}
		}


		if($ds->row["id_parent"]!=$category3 and $category2!=$category3 and (int)$category3!=0)
		{
			$sql="select title from category where id_parent=".(int)$category3;
			$dr->open($sql);
			if(!$dr->eof)
			{
				$translate_results=translate_category((int)$category3,$dr->row["title"],"","");
				
				$category_list.=" <a href='".category_url($category3)."'>".$translate_results["title"]."</a>";
			}
		}
	}


return $category_list;
}
//End. The function shows a publication's category




//The function shows a publication's rating
function show_rating($id,$rating)
{
	global $db;
	global $ds;
	global $site_template_url;
	global $global_settings;
	global $_SESSION;
	global $boxcontent;

	$item_rating=(float)$rating;

	if(!isset($_SESSION["people_id"]) and $global_settings["auth_rating"])
	{
		$rating_link="<a href='".site_root."/members/login.php'>";
	}
	else
	{
		$sql="select ip,id from voteitems where ip='".result($_SERVER["REMOTE_ADDR"])."' and id=".(int)$id;
		$ds->open($sql);
		if(!$ds->eof)
		{
			$rating_link="<a href='#'>";
		}
		else
		{
			$rating_link="<a href=\"javascript:doVote('{vote}');\">";
		}
	}

	$rating_text="<div id='votebox' name='votebox'>";
	

	for($j=1;$j<6;$j++)
	{
		$tt="2";
		
		if($j<=$item_rating or $j-$item_rating<=0.25){$tt="1";}
		
		if($j>$item_rating and $j-$item_rating>0.25 and $j-$item_rating<0.75){$tt="3";}
		
		$rating_text.="".str_replace("{vote}",strval($j),$rating_link)."<img src='{TEMPLATE_ROOT}images/rating".$tt.".gif' width='11' id='rating".$j."' onMouseover='mrating(".$j.");' onMouseout='mrating2(".$item_rating.");'  height='11' class='rating' border='0'></a>";
	}



	$rating_text.="</div>";
	
	
	$rating_text2="<script>
    			$(function() {
      				$('#star".$id."').raty({
      				score: ".$rating.",
 					half: true,
  					number: 5,
  					click: function(score, evt) {
    					vote_rating(".$id.",score);
  					}
				});
    			});
				</script>
				<div id='star".$id."' style='display:inline'></div>";
	
	$boxcontent=str_replace("{ITEM_RATING}",$rating_text,$boxcontent);
	$boxcontent=str_replace("{ITEM_RATING_NEW}",$rating_text2,$boxcontent);
	
	return $rating_text;
}
//End. The function shows a publication's rating





//The function shows a publication's related items
function show_related_items($id,$type)
{
global $db;
global $ds;
global $keywords;
global $titles;
global $boxcontent;
global $global_settings;
global $site_template_url;

$related_table["photo"]="photos";
$related_table["video"]="videos";
$related_table["audio"]="audio";
$related_table["vector"]="vector";

$related_items="";
$related_items2="";
$sqlkey="";


	for($k=0;$k<count($keywords);$k++)
	{
		if($sqlkey!=""){$sqlkey.=" or ";}
		//$sqlkey.=" b.title like '%".$keywords[$k]."%' or b.description like '%".$keywords[$k]."%' or b.keywords like '%".$keywords[$k]."%' ";
		
		$sqlkey.=" b.keywords like '%".$keywords[$k]."%' ";
	}

/*
	$titles2=array();
	$kk=0;
	for($k=0;$k<count($titles);$k++)
	{
		if(strlen($titles[$k])>2)
		{
			$titles2[$kk]=$titles[$k];
			$kk++;
		}
	}
	
	if(isset($titles2[0]) and !isset($titles2[1]))
	{	
		$sqlkey.=" b.title like '%".$titles2[0]."%' ";
	}
	
	if(isset($titles2[0]) and isset($titles2[1]))
	{	
		$sqlkey.=" (b.title like '%".$titles2[0]."%' and b.title like '%".$titles2[1]."%') ";
	}

	if(isset($titles2[1]) and isset($titles2[2]))
	{	
		$sqlkey.=" or ";
		$sqlkey.=" (b.title like '%".$titles2[1]."%' and b.title like '%".$titles2[2]."%') ";
	}
	
	if(isset($titles2[2]) and isset($titles2[3]))
	{	
		$sqlkey.=" or ";
		$sqlkey.=" (b.title like '%".$titles2[2]."%' and b.title like '%".$titles2[3]."%') ";
	}
*/
	
	if($sqlkey!="")
	{
		$tt=0;
		
		$count_random=0;
		$limit_random=" limit 0,".$global_settings["related_items_quantity"];
		
		/*
		$sql_random="select count(*) as count_rows from ".$related_table[$type]." b where b.published=1 and b.id_parent<>".(int)$id." and (".$sqlkey.") ".get_password_protected();
		$ds->open($sql_random);
		if(!$ds->eof)
		{
			$count_random=$ds->row["count_rows"];
			$rnd=rand(0,$count_random);
			if($count_random-$rnd<$global_settings["related_items_quantity"])
			{
				$rnd=$count_random-$global_settings["related_items_quantity"];
				if($rnd<0)
				{
					$rnd=0;
				}
			}
			$limit_random=" limit ".$rnd.",".$global_settings["related_items_quantity"];
		}	
		*/
		
		
		$protected_categories=get_password_protected();
		
		if($protected_categories!="")
		{
			$sql="select a.id,a.id_parent,b.id_parent,b.title,b.server1,b.url,b.author,b.description from structure a,".$related_table[$type]." b where a.id=b.id_parent and b.published=1 and b.id_parent<>".(int)$id." and (".$sqlkey.") ".get_password_protected().$limit_random;
		}
		else
		{
			$sql="select  b.id_parent,b.title,b.server1,b.url,b.author,b.description from ".$related_table[$type]." b where b.published=1 and b.id_parent<>".(int)$id." and (".$sqlkey.") ".$limit_random;
		}
		
		/*
		$sql="select id_parent from structure where id=".(int)$id;
		$ds->open($sql);
		$id_category=$ds->row["id_parent"];
		
		$sql="select a.id,a.id_parent,b.id_parent,b.title,b.server1,b.url,b.author from structure a,".$related_table[$type]." b where a.id=b.id_parent and b.published=1 and a.id_parent=".$id_category." and b.id_parent<>".(int)$id." and (".$sqlkey.") ".get_password_protected().$limit_random;
		*/

		$ds->open($sql);
		
		if(!$ds->eof)
		{
			$related_items.="<div class=\"sc_menu\"><ul class=\"sc_menu\">";
			$item_content="";
			
			if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."item_related.tpl"))
			{
				$item_content=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."item_related.tpl");
			}
			
			while(!$ds->eof)
			{	
				if($tt<$global_settings["related_items_quantity"])
				{
					$item_text=$item_content;
					
					
					$preview_type=1;
					if($type=="video" or $type=="audio")
					{
						$preview_type=3;
					}
						
					$hoverbox_results=get_hoverbox($ds->row["id_parent"],$type,$ds->row["server1"],$ds->row["title"],show_user_name($ds->row["author"]));
					
					$preview=show_preview($ds->row["id_parent"],$type,$preview_type,1);
						
					$related_items.="<li><div class='sc_menu_div' style='background:url(".$preview.");background-size:cover'><a href='".item_url($ds->row["id_parent"],$ds->row["url"])."'><img src='".site_root."/images/e.gif' alt='".$ds->row["title"]."' border='0' ".$hoverbox_results["hover"]."></a></div></li>";
					
					$item_text=str_replace("{ITEM_ID}",$ds->row["id_parent"],$item_text);	
					$item_text=str_replace("{TITLE}",$ds->row["title"],$item_text);	
					$item_text=str_replace("{DESCRIPTION}",$ds->row["description"],$item_text);
					$item_text=str_replace("{URL}",item_url($ds->row["id_parent"],$ds->row["url"]),$item_text);
					$item_text=str_replace("{PREVIEW}",$hoverbox_results["flow_image"],$item_text);
					$item_text=str_replace("{LIGHTBOX}",$hoverbox_results["hover"],$item_text);
					
					$str_width=" width='".$hoverbox_results["flow_width"]."' ";
					$str_height=" height='".$hoverbox_results["flow_height"]."' ";
					$str_width2="width:".$hoverbox_results["flow_width"]."px";
					$str_height2="height:".$hoverbox_results["flow_height"]."px";
					
					$item_text=str_replace("{WIDTH}",$str_width,$item_text);
					$item_text=str_replace("{HEIGHT}",$str_height,$item_text);

					$item_text=str_replace("{WIDTH2}",$str_width2,$item_text);
					$item_text=str_replace("{HEIGHT2}",$str_height2,$item_text);
					
					$item_text=str_replace("{SITE_ROOT}",site_root,$item_text);
									
					$related_items2.=$item_text;
				}
				$tt++;
				
			$ds->movenext();
			}

			$related_items.="</ul></div>";
		}
	}


	$boxcontent=str_replace("{WORD_RELATED_ITEMS}",word_lang("related items"),$boxcontent);

	$boxcontent=str_replace("{RELATED_ITEMS}",$related_items,$boxcontent);
	$boxcontent=str_replace("{RELATED_ITEMS2}",$related_items2,$boxcontent);
	$arelated_items = array();
	
	$flag_related=false;
	if($global_settings["related_items"] and $related_items!="")
	{
		$flag_related=true;
	}
	$boxcontent=format_layout($boxcontent,"related_items",$flag_related);
}
//End. The function shows a publication's related items




//The function shows a publication's add to favorite button
function show_favorite($id)
{
global $db;
global $dr;
global $boxcontent;
global $_SESSION;



$boxcontent=str_replace("{ADD_TO_FAVORITE}",word_lang("add to favorite list"),$boxcontent);
$boxcontent=str_replace("{ADD_TO_FAVORITE_LINK}","javascript:show_lightbox(".(int)$id.",'".site_root."')",$boxcontent);


}
//End. The function shows a publication's add to favority button




//The function shows a publication's author
function show_author($author)
{
global $db;
global $dr;
global $boxcontent;

	$boxauthor=show_user_avatar($author,"login");
	$boxcontent=str_replace("{PORTFOLIO_LINK}",site_root."/index.php?user=".user_url($author)."&portfolio=1",$boxcontent);

	$boxcontent=str_replace("{AUTHOR}",$boxauthor,$boxcontent);
}
//End. The function shows a publication's author



//The function shows a publication's keywords
function show_keywords($id,$type)
{
	global $db;
	global $rs;
	global $dr;
	global $boxcontent;
	global $keywords;
	global $global_settings;
	global $site_template_url;
	global $translate_results;

	$kw="";
	$kw_lite="";
	if($translate_results["keywords"]!="")
	{
		$keywords=explode(",",str_replace(";",",",$translate_results["keywords"]));
		for($i=0;$i<count($keywords);$i++)
		{
			$keywords[$i]=trim($keywords[$i]);
			if($keywords[$i]!="")
			{
				$kw.="<div style='margin-bottom:3px'><input type='checkbox' name='s_".str_replace(" ","_",$keywords[$i])."'>&nbsp;<a href='".site_root."/?search=".$keywords[$i]."'>".$keywords[$i]."</a></div>";
				$kw_lite.="<a href='".site_root."/?search=".$keywords[$i]."' class='kw'>".$keywords[$i]."</a> ";
			}
		}
		
		if($global_settings["watermarkinfo"] and $type=="photo")
		{
		
			$wposit="<table border='0' cellpadding='0' cellspacing='1'><tr>";
			for($i=1;$i<10;$i++)
			{
				$wsel="b";
				if($global_settings["watermark_position"]==$i){$wsel="o";}

				if(site_root==""){$wpath="/";}
				else{$wpath=site_root."/";}

				$wposit.="<td><img src='".$wpath.$site_template_url."images/".$wsel.".gif' width='5' height='5'></td>";

				if($i%3==0){$wposit.="</tr><tr>";}
			}
			$wposit.="</tr></table>";

		
			$kw.="<div style='margin-bottom:3px'><table border='0' cellpadding='0' cellspacing='0'><tr><td><input type='checkbox' name='swatermark' value='".$rs->row["watermark"]."'></td><td>&nbsp;</td><td>".word_lang("watermark")."</td><td>&nbsp;</td><td>".$wposit."</td></tr></table></div>";
		}
	}
	
	if($kw!="")
	{
		$boxcontent=str_replace("{WORD_KEYWORDS}",word_lang("keywords").":",$boxcontent);
		$boxcontent=str_replace("{KEYWORDS}","<form method='get' action='".site_root."/' style='margin:0px'>".$kw."<input type='submit' value='".word_lang("search")."'></form>",$boxcontent);
		$boxcontent=str_replace("{KEYWORDS_LITE}",$kw_lite,$boxcontent);
	}
	else
	{
		$boxcontent=str_replace("{WORD_KEYWORDS}","",$boxcontent);
		$boxcontent=str_replace("{KEYWORDS}","",$boxcontent);
		$boxcontent=str_replace("{KEYWORDS_LITE}","",$boxcontent);
	}



}
//End. The function shows a publication's keywords




//The function shows a publication's community tools
function show_community()
{
global $boxcontent;
global $global_settings;
global $db;
global $rs;
global $dn;

	$boxcontent=format_layout($boxcontent,"reviews",$global_settings["reviews"]);
	$boxcontent=format_layout($boxcontent,"messages",$global_settings["messages"]);

	
	$models_list="";
	$flag1=word_lang("no");
	$flag2=word_lang("no");
	
	$sql="select models from models_files where publication_id=".$rs->row["id_parent"];
	$dn->open($sql);
	while(!$dn->eof)
	{
		if($dn->row["models"]==0)
		{
			$flag1=word_lang("yes");
		}
		else
		{
			$flag2=word_lang("yes");
		}
		$dn->movenext();
	}
	
	$models_list.="<span><b>".word_lang("Model release").":</b> ".$flag1."</span>";
	$models_list.="<span><b>".word_lang("Property release").":</b> ".$flag2."</span>";

	$boxcontent=str_replace("{MODEL}",$models_list,$boxcontent);
	
	$flag_model=false;
	if($global_settings["model"] and $global_settings["show_model"])
	{
		$flag_model=true;
	}
	$boxcontent=format_layout($boxcontent,"model",$flag_model);


	
	$boxcontent=str_replace("{WORD_BACK}",word_lang("back to results"),$boxcontent);	

	if(isset($_SERVER["HTTP_REFERER"]) and $_SERVER["HTTP_REFERER"]!="")
	{	
		$boxcontent=str_replace("{LINK_BACK}",$_SERVER["HTTP_REFERER"],$boxcontent);
	}
	
	$flag_back=false;
	if(isset($_SERVER["HTTP_REFERER"]) and $_SERVER["HTTP_REFERER"]!="")
	{
		$flag_back=true;
	}
	$boxcontent=format_layout($boxcontent,"back",$flag_back);
	
}
//End. The function shows a publication's community tools







//The function shows Google map
function show_google_map($x,$y)
{
global $boxcontent;
global $global_settings;

	$boxcontent=str_replace("{WORD_GOOGLE}",word_lang("Show on Google map"),$boxcontent);
	$boxcontent=str_replace("{GOOGLE_X}",$x,$boxcontent);
	$boxcontent=str_replace("{GOOGLE_Y}",$y,$boxcontent);

	$flag_google=false;
	if($global_settings["google_coordinates"] and (float)$x!=0 and (float)$y!=0)
	{
		$flag_google=true;
	}
	$boxcontent=format_layout($boxcontent,"google",$flag_google);	
}
//End. The function shows Google map





//The function shows EXIF info
function show_exif($id)
{
global $boxcontent;
global $global_settings;
global $flag_storage;

	$boxcontent=str_replace("{WORD_EXIF}",word_lang("Show EXIF info"),$boxcontent);

	$flag_exif=false;
	if($global_settings["exif"] and !$flag_storage)
	{
		$flag_exif=true;
	}
	$boxcontent=format_layout($boxcontent,"exif",$flag_exif);	
}
//End. The function shows EXIF info






//The function shows next/previous navigation
function show_navigation($id,$type)
{
	global $db;
	global $dr;
	global $boxcontent;
	$navigation="";
	$previous_link="";
	$next_link="";

	$idp=0;
	$sql="select id_parent from structure where id=".(int)$id;
	$dr->open($sql);
	if(!$dr->eof)
	{
		$idp=$dr->row["id_parent"];
	}
	
	$sql="select a.id,a.id_parent,b.id_parent from structure a,".$type." b where a.id=b.id_parent and a.id_parent=".$idp." and a.id<".(int)$id." order by a.id desc";
	$dr->open($sql);
	if(!$dr->eof)
	{
		$navigation.="<a href='".item_url($dr->row["id_parent"])."' class='previous_link'>&#171; ".word_lang("Previous")."</a>";
		$previous_link=item_url($dr->row["id_parent"]);
	}
	
	$sql="select a.id,a.id_parent,b.id_parent from structure a,".$type." b where a.id=b.id_parent and a.id_parent=".$idp." and a.id>".(int)$id." order by a.id";
	$dr->open($sql);
	if(!$dr->eof)
	{
		$navigation.=" <a href='".item_url($dr->row["id_parent"])."' class='next_link'>".word_lang("Next")."&#187; </a>";
		$next_link=item_url($dr->row["id_parent"]);
	}

	$boxcontent=str_replace("{NAVIGATION}",$navigation,$boxcontent);
	$boxcontent=str_replace("{PREVIOUS_LINK}",$previous_link,$boxcontent);
	$boxcontent=str_replace("{NEXT_LINK}",$next_link,$boxcontent);
	
	$flag_previous=false;
	if($previous_link!="")
	{
		$flag_previous=true;
	}
	
	$flag_next=false;
	if($next_link!="")
	{
		$flag_next=true;
	}
	
	$boxcontent=format_layout($boxcontent,"previous",$flag_previous);
	$boxcontent=format_layout($boxcontent,"next",$flag_next);
	
	
}
//End. The function shows next/previous navigation
?>