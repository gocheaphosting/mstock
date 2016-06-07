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




//Create search URL
$url = 'https://api.gettyimages.com/v3/search/images?fields=thumb,preview,max_dimensions,title,comp,referral_destinations&phrase=' . urlencode(result(@$_REQUEST["search"]));

if(@$_REQUEST["license"] == 'creative')
{
	$url = 'https://api.gettyimages.com/v3/search/images/creative?fields=thumb,preview,max_dimensions,title,comp,referral_destinations&phrase=' . urlencode(result(@$_REQUEST["search"]));
}

if(@$_REQUEST["license"] == 'editorial')
{
	$url = 'https://api.gettyimages.com/v3/search/images/editorial?fields=thumb,preview,max_dimensions,title,comp,referral_destinations&phrase=' . urlencode(result(@$_REQUEST["search"]));
}

$istocktphoto_type = "photo";

if(@$_REQUEST["stock_type"] == "videos")
{
	$url = 'https://api.gettyimages.com/v3/search/videos?fields=thumb,preview,title,comp&phrase=' . urlencode(result(@$_REQUEST["search"]));
	
	if(@$_REQUEST["license"] == 'creative')
	{
		$url = 'https://api.gettyimages.com/v3/search/videos/creative?fields=thumb,preview,title,comp&phrase=' . urlencode(result(@$_REQUEST["search"]));
	}
	
	if(@$_REQUEST["license"] == 'editorial')
	{
		$url = 'https://api.gettyimages.com/v3/search/videos/editorial?fields=thumb,preview,title,comp&phrase=' . urlencode(result(@$_REQUEST["search"]));
	}
	
	$istocktphoto_type = "videos";
}



//Page
$url .= '&page=' . @$str . '&page_size=' . @$items;



//Sort
if(@$_REQUEST["sort"] != "" and (@$_REQUEST["sort"] == 'best_match' or @$_REQUEST["sort"] == 'most_popular' or @$_REQUEST["sort"] == 'newest'))
{
	$url .= '&sort_order=' . result($_REQUEST["sort"]);
}
else
{
	$url .= '&sort_order=best_match';
}



//Contributor
if(@$_REQUEST["stock_type"] != "videos")
{
	if(@$_REQUEST["author"] != "")
	{
		$url .= '&artists=' . urlencode(result($_REQUEST["author"]));
	}
	else
	{
		if($global_settings["istockphoto_contributor"] != "")
		{
			$url .= '&artists=' . urlencode($global_settings["istockphoto_contributor"]);
		}
	}
}


//Collection
if(isset($_REQUEST["category"]) and $_REQUEST["category"] != ""  and $_REQUEST["category"] != -1)
{
	$url .= '&collections_filter_type=include&collection_codes=' . urlencode(result($_REQUEST["category"]));
}


//Orientation
if(@$_REQUEST["orientation"] != "" and @$_REQUEST["stock_type"] != "videos")
{
	$url .= '&orientations=' . result($_REQUEST["orientation"]);
}


//Age
if(@$_REQUEST["age"] != "")
{
	$url .= '&age_of_people=' . result($_REQUEST["age"]);
}


//Ethnicity
if(@$_REQUEST["ethnicity"] != ""  and @$_REQUEST["stock_type"] != "videos")
{
	$url .= '&ethnicity=' . result($_REQUEST["ethnicity"]);
}

//People number
if(@$_REQUEST["people_number"] != ""  and @$_REQUEST["stock_type"] != "videos")
{
	$url .= '&number_of_people=' . result($_REQUEST["people_number"]);
}

//Compositions
if(@$_REQUEST["compositions"] != ""  and @$_REQUEST["stock_type"] != "videos")
{
	$url .= '&compositions=' . result($_REQUEST["compositions"]);
}

//File types
if(@$_REQUEST["file_types"] != ""  and @$_REQUEST["stock_type"] != "videos")
{
	$url .= '&file_types=' . result($_REQUEST["file_types"]);
}

//Graphical styles
if(@$_REQUEST["graphical_styles"] != ""  and @$_REQUEST["stock_type"] != "videos")
{
	$url .= '&graphical_styles=' . result($_REQUEST["graphical_styles"]);
}

//License models
if(@$_REQUEST["license_models"] != "")
{
	$url .= '&license_models=' . result($_REQUEST["license_models"]);
}




//Resolution
if(@$_REQUEST["resolution"] != "" and @$_REQUEST["stock_type"] == "videos")
{
	$url .= '&format_available=' . result($_REQUEST["resolution"]);
}



//echo($url."<br><br>");

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Api-Key: '.$global_settings["istockphoto_id"]));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

$data = curl_exec($ch); 
if (!curl_errno($ch)) 
{
    $results=json_decode($data);
    //var_dump($results);
    $n = 0;
    if(isset($results->images) or isset($results->videos))
    {
		if($istocktphoto_type == "photo")
		{
			$data = $results->images;
		}
		else
		{
			$data = $results->videos;
		}
		
		foreach ($data as $key => $value) 
		{
			$n++;
			
			$itembox = $boxcontent;
			$itembox = str_replace("{ITEM_TITLE}","#".@$value->id,$itembox);
			$itembox = str_replace("{ITEM_ID}",@$value->id,$itembox);
			
			
			
			$itembox=preg_replace('|\{if cart\}(.*)\{/if\}|Uis',"",$itembox);
			$itembox=preg_replace('|\{if cartflow\}(.*)\{/if\}|Uis',"",$itembox);
			$itembox=preg_replace('|\{if cartflow2\}(.*)\{/if\}|Uis',"",$itembox);
			$itembox=preg_replace('|\{if featured\}(.*)\{/if\}|Uis',"",$itembox);
			$itembox=preg_replace('|\{if new\}(.*)\{/if\}|Uis',"",$itembox);
			$itembox=str_replace('{CLASS2}',"",$itembox);
			
			$itembox=str_replace("{ITEM_VIEWED}","",$itembox);
			$itembox=str_replace("{DOWNLOADS}","",$itembox);
			
			//Image
			if($istocktphoto_type == "photo")
			{	
				$itembox = str_replace("{ITEM_TITLE_FULL}",@$value->title,$itembox);
				
				$istockphoto_image = @$value->display_sizes;
				$istockphoto_thumb = $istockphoto_image[2];
				$istockphoto_preview = $istockphoto_image[0];
				
				$itembox = str_replace("{ITEM_IMG}",$istockphoto_thumb->uri,$itembox);
				
				
				$lightbox_width=@$value->max_dimensions->width;
				$lightbox_height=@$value->max_dimensions->height;
				$lightbox_url=$istockphoto_preview->uri;
				
				$global_settings["max_hover_size"] = 340;
				
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
				
				$lightbox_hover="";
				
				$lightbox_hover="onMouseover=\"lightboxon('".$lightbox_url."',".$lightbox_width.",".$lightbox_height.",event,'".site_root."','".addslashes(str_replace("'","",str_replace("\n","",str_replace("\r","",@$value->title))))	."','');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$lightbox_width.",".$lightbox_height.",event)\"";
	
				$itembox=str_replace("{ITEM_IMG2}",$lightbox_url,$itembox);
	
				$flow_width=$lightbox_width;
				$flow_height=$lightbox_height;		
			}
			//End image
			
			//Video
			if($istocktphoto_type == "videos")
			{	
				$itembox = str_replace("{ITEM_TITLE_FULL}",@$value->title,$itembox);
				
				$istockphoto_preview2 = @$value->display_sizes;
				$istockphoto_image2 = $istockphoto_preview2[2];
				$istockphoto_video2 = $istockphoto_preview2[0];
				
				$itembox = str_replace("{ITEM_IMG}",@$istockphoto_image2->uri,$itembox);
				$itembox = str_replace("{ITEM_IMG2}",@$istockphoto_image2->uri,$itembox);
				
				$video_width=$global_settings["video_width"];
				$video_height=round($global_settings["video_width"]*9/16);
				
				$lightbox_hover="onMouseover=\"lightboxon_istock('".@$istockphoto_video2->uri."',".$video_width.",".$video_height.",event,'".site_root."');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$video_width.",".$video_height.",event)\"";
	
				$flow_width=$global_settings["width_flow"];
				$flow_height=round($global_settings["width_flow"]*9/16);
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
			
			
			if($global_settings["istockphoto_pages"])
			{
				$itembox=str_replace("{ITEM_URL}",get_stock_page_url("istockphoto",@$value->id,@$value->title,$istocktphoto_type),$itembox);
			}
			else
			{
				$referal_url = @$value-> referral_destinations;
				
				$aff_url=get_stock_affiliate_url("istockphoto",@$value->id,$istocktphoto_type,@$referal_url[0]->uri,@$referal_url[1]->uri);
				
				$itembox=str_replace("{ITEM_URL}'",$aff_url."' target='blank'",$itembox);
				$itembox=str_replace('{ITEM_URL}"',$aff_url.'" target="blank"',$itembox);
			}
			
			$itembox=translate_text($itembox);
			
			$search_content .= $itembox;
		}
	}
	
	if($n == 0 and $str == 1)
	{
		$search_content = word_lang("not found");
	}
	
	$stock_result_count = @$results->result_count;
}
else
{
	echo(word_lang("Error. The script cannot connect to API."));
}

curl_close($ch); 

?>