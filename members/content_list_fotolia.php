<?
if(!defined("site_root")){exit();}



$flag_empty=false;
$search_content="";

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


$auth=base64_encode ($global_settings["fotolia_id"].":");

//Create search URL:

if(isset($_REQUEST["stock_type"]))
{
	if($_REQUEST["stock_type"] == "")
	{
		$url = 'http://api.fotolia.com/Rest/1/search/getSearchResults?search_parameters[filters][content_type:all]=1';
	}
	
	if($_REQUEST["stock_type"] == "photo" or $_REQUEST["stock_type"] == "illustration" or $_REQUEST["stock_type"] == "vector")
	{
		$url = 'http://api.fotolia.com/Rest/1/search/getSearchResults?search_parameters[filters][content_type:' . $_REQUEST["stock_type"] . ']=1';
	}
	
	if($_REQUEST["stock_type"] == "videos")
	{
		$url = 'http://api.fotolia.com/Rest/1/search/getSearchResults?search_parameters[filters][content_type:video]=1';
	}
}
else
{
	$url = 'http://api.fotolia.com/Rest/1/search/getSearchResults?search_parameters[filters][content_type:all]=1';
}

//Search
if($search != '')
{
	$url .= '&search_parameters[words]=' . urlencode($search);
}
else
{
	$url .= '&search_parameters[words]='.urlencode($global_settings["fotolia_query"]);
}

//Page
$url .= '&search_parameters[offset]=' . ((@$str - 1)* @$items) . '&search_parameters[limit]=' . @$items;


//Sort
if(@$_REQUEST["sort"] != "" and (@$_REQUEST["sort"] == 'relevance' or @$_REQUEST["sort"] == 'price_1' or @$_REQUEST["sort"] == 'creation' or @$_REQUEST["sort"] == 'nb_views' or @$_REQUEST["sort"] == 'nb_downloads'))
{
	$url .= '&search_parameters[order]=' . result($_REQUEST["sort"]);
}
else
{
	$url .= '&search_parameters[order]=relevance';
}


//Contributor
if(@$_REQUEST["author"] != "")
{
	$url .= '&search_parameters[creator_id]=' . result($_REQUEST["author"]);
}
else
{
	if($global_settings["fotolia_contributor"] != "")
	{
		$url .= '&search_parameters[creator_id]=' . $global_settings["fotolia_contributor"];
	}
}


//Category
if(isset($_REQUEST["category"]) and $_REQUEST["category"] != -1)
{
	$url .= '&search_parameters[cat1_id]=' . (int)$_REQUEST["category"];
}
else
{
	if(!isset($_REQUEST["category"]) and $global_settings["fotolia_category"] != -1)
	{
		$url .= '&search_parameters[cat1_id]=' . $global_settings["fotolia_category"];
	}
}

//Language
if(@$_REQUEST["language"] != "")
{
	$url .= '&search_parameters[language_id]=' . (int)$_REQUEST["language"];
}


//License
if(@$_REQUEST["license"] != "" and @$_REQUEST["stock_type"] != "videos")
{
	$url .= '&search_parameters[filters][license_' . result($_REQUEST["license"]) . ':on]=1';
}



//Orientation
if(@$_REQUEST["orientation"] != "" and @$_REQUEST["orientation"] != "-1" and @$_REQUEST["stock_type"] != "videos")
{
	$url .= '&search_parameters[filters][orientation]=' . result($_REQUEST["orientation"]);
}



//Color
if(@$_REQUEST["color"] != "" and @$_REQUEST["stock_type"] != "videos")
{
	$url .= '&search_parameters[filters][colors]=' . result($_REQUEST["color"]);
}


//Model property release
if(isset($_REQUEST["model"]))
{
	$url .= '&search_parameters[filters][has_releases]=1';
}

//Resolution
if(@$_REQUEST["resolution"] != "" and @$_REQUEST["stock_type"] == "videos")
{
	$url .= '&search_parameters[filters][license_V_' . result($_REQUEST["resolution"]) . ':on]=1';
}



//Aspect ratio
if(@$_REQUEST["duration"] != "" and @$_REQUEST["stock_type"] == "videos")
{
	$url .= '&search_parameters[filters][video_duration]=' . result($_REQUEST["duration"]);
}



//echo($url."<br><br>");

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $auth));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

$data = curl_exec($ch); 
if (!curl_errno($ch)) 
{
    $results=json_decode($data);
    //var_dump($results);
    $n = 0;
    if(is_object($results ))
    {
		foreach ($results as $key => $value) 
		{
			$n++;
			
			if(isset($value->id))
			{
				$itembox = $boxcontent;
				$itembox = str_replace("{ITEM_TITLE}","#".@$value->id,$itembox);
				$itembox = str_replace("{ITEM_ID}",@$value->id,$itembox);
				
				
				
				$itembox=preg_replace('|\{if cart\}(.*)\{/if\}|Uis',"",$itembox);
				$itembox=preg_replace('|\{if cartflow\}(.*)\{/if\}|Uis',"",$itembox);
				$itembox=preg_replace('|\{if cartflow2\}(.*)\{/if\}|Uis',"",$itembox);
				$itembox=preg_replace('|\{if featured\}(.*)\{/if\}|Uis',"",$itembox);
				$itembox=preg_replace('|\{if new\}(.*)\{/if\}|Uis',"",$itembox);
				$itembox=str_replace('{CLASS2}',"",$itembox);
				
				$itembox=str_replace("{ITEM_VIEWED}",@$value->nb_views,$itembox);
				$itembox=str_replace("{DOWNLOADS}",@$value->nb_downloads,$itembox);
				
				//Image
				if(@$value->media_type_id == 1 or @$value->media_type_id == 2 or @$value->media_type_id == 3)
				{			
					$itembox = str_replace("{ITEM_TITLE_FULL}",@$value->title,$itembox);
					
					$itembox = str_replace("{ITEM_IMG}",@$value->thumbnail_400_url,$itembox);
					
					$lightbox_width=@$value->thumbnail_400_width;
					$lightbox_height=@$value->thumbnail_400_height;
					$lightbox_url=@$value->thumbnail_400_url;
					
					if($lightbox_width>$lightbox_height)
					{
						if($lightbox_width>$global_settings["max_hover_size"])
						{
							
							$lightbox_height=round($lightbox_height*$global_settings["max_hover_size"]/$lightbox_width);
							$lightbox_width=$global_settings["max_hover_size"];
						}
					}
					else
					{
						if($lightbox_height>$global_settings["max_hover_size"])
						{
							$lightbox_width=round($lightbox_width*$global_settings["max_hover_size"]/$lightbox_height);
							$lightbox_height=$global_settings["max_hover_size"];
						}				
					}
					
					$lightbox_hover="onMouseover=\"lightboxon('".$lightbox_url."',".$lightbox_width.",".$lightbox_height.",event,'".site_root."','".addslashes(str_replace("'","",str_replace("\n","",str_replace("\r","",@$value->title))))	."','');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$lightbox_width.",".$lightbox_height.",event)\"";
		
					$itembox=str_replace("{ITEM_IMG2}",$lightbox_url,$itembox);
		
					$flow_width=@$value->thumbnail_400_width;
					$flow_height=@$value->thumbnail_400_height;		
				}
				//End image
				
				//Video
				if(@$value->media_type_id == 4)
				{	
					$itembox = str_replace("{ITEM_TITLE_FULL}",@$value->title,$itembox);
					
					$itembox = str_replace("{ITEM_IMG}",@$value->thumbnail_400_url,$itembox);
					$itembox = str_replace("{ITEM_IMG2}",@$value->thumbnail_400_url,$itembox);
					
					$video_width=$global_settings["video_width"];
					$video_height=round($global_settings["video_width"]*@$value->thumbnail_400_height/@$value->thumbnail_400_width);
					
					$video_mp4 = @$value->video_data->formats->comp->url;
					$lightbox_hover="onMouseover=\"lightboxon5('".@$video_mp4."',".$video_width.",".$video_height.",event,'".site_root."');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$video_width.",".$video_height.",event)\"";
	
					$flow_width=$global_settings["width_flow"];
					$flow_height=round($global_settings["width_flow"]*@$value->thumbnail_400_height/@$value->thumbnail_400_width);
				}
	
				//End. Video
	
				
				$itembox=str_replace("{ITEM_LIGHTBOX}",$lightbox_hover,$itembox);
				
				$width_limit=$global_settings["width_flow"];
				
				if(($flow_width>$width_limit or $flow_height>$width_limit) and $flow_width!=0)
				{
					$flow_height=round($flow_height*$width_limit/$flow_width);
					$flow_width=$width_limit;
				}
				
				
				if($flow==1 or $flow==2)
				{	
					$str_width=" width='".$flow_width."' ";
					$str_height=" height='".$flow_height."' ";
			
					$str_width2="width:".$flow_width."px";
					$str_height2="height:".$flow_height."px";
				}
				
				if($flow==0)
				{	
					$str_width=" width='".$flow_width."' ";
					$str_height=" height='".$flow_height."' ";
			
					$str_width2="width:".$flow_width."px";
					$str_height2="height:".$flow_height."px";
				}
		
				$itembox=str_replace("{WIDTH}",$str_width,$itembox);
				$itembox=str_replace("{HEIGHT}",$str_height,$itembox);
		
				$itembox=str_replace("{WIDTH2}",$str_width2,$itembox);
				$itembox=str_replace("{HEIGHT2}",$str_height2,$itembox);
				
				
				$itembox=str_replace("{ITEM_DESCRIPTION}","",$itembox);
				$itembox=str_replace("{ITEM_KEYWORDS}","",$itembox);	
				$itembox=str_replace("{TEMPLATE_ROOT}",site_root."/".$site_template_url,$itembox);
				$itembox=str_replace("{SITE_ROOT}",site_root,$itembox);
				
				$fotolia_type = "photo";
				if(@$value->media_type_id == 4)
				{
					$fotolia_type = "video";
				}
				
				if($global_settings["fotolia_pages"])
				{
					$itembox=str_replace("{ITEM_URL}",get_stock_page_url("fotolia",@$value->id,@$value->title,$fotolia_type),$itembox);
				}
				else
				{
					$aff_url=get_stock_affiliate_url("fotolia",@$value->id,$fotolia_type,@$value->affiliation_link);
					
					$itembox=str_replace("{ITEM_URL}'",$aff_url."' target='blank'",$itembox);
					$itembox=str_replace('{ITEM_URL}"',$aff_url.'" target="blank"',$itembox);
				}
				
				$itembox=translate_text($itembox);
				
				$search_content .= $itembox;
			}
		}
	}
		
	if($n == 0 and $str == 1)
	{
		$search_content = word_lang("not found");
	}
	
	$stock_result_count = @$results->nb_results;

}
else
{
	echo(word_lang("Error. The script cannot connect to API."));
}

curl_close($ch); 

?>