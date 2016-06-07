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




//Create search URL:

$url = 'http://api.depositphotos.com?dp_apikey=' . $global_settings["depositphotos_id"] . '&dp_command=search&dp_search_query=' . urlencode($search);

if(@$_REQUEST["stock_type"] != '')
{	
	if($_REQUEST["stock_type"] == "photo")
	{
		$url = 'http://api.depositphotos.com?dp_apikey=' . $global_settings["depositphotos_id"] . '&dp_command=search&dp_search_photo=1&dp_search_vector=0&dp_search_video=0&dp_search_query=' . urlencode($search);
	}
	
	if($_REQUEST["stock_type"] == "vector")
	{
		$url = 'http://api.depositphotos.com?dp_apikey=' . $global_settings["depositphotos_id"] . '&dp_command=search&dp_search_vector=1&dp_search_photo=0&dp_search_video=0&dp_search_query=' . urlencode($search);
	}
	
	if($_REQUEST["stock_type"] == "videos")
	{
		$url = 'http://api.depositphotos.com?dp_apikey=' . $global_settings["depositphotos_id"] . '&dp_command=search&dp_search_video=1&dp_search_photo=0&dp_search_vector=0&dp_search_query=' . urlencode($search);
	}
}


//Page
$url .= '&dp_search_offset=' . ((@$str-1)*@$items) . '&dp_search_limit=' . @$items;


//Sort
if(@$_REQUEST["sort"] != "" and (@$_REQUEST["sort"] == 1 or @$_REQUEST["sort"] == 4 or @$_REQUEST["sort"] == 5))
{
	$url .= '&dp_search_sort=' . (int)$_REQUEST["sort"];
}
else
{
	$url .= '&dp_search_sort=4';
}





//Contributor

if(@$_REQUEST["author"] != "")
{
	$url .= '&dp_search_username=' . result($_REQUEST["author"]);
}
else
{
	if($global_settings["depositphotos_contributor"] != "")
	{
		$url .= '&dp_search_username=' . $global_settings["depositphotos_contributor"];
	}
}




//Category
if(isset($_REQUEST["category"]) and $_REQUEST["category"] != -1 and (int)$_REQUEST["category"] != 0)
{
	$url .= '&dp_search_categories=' . (int)$_REQUEST["category"];
}
else
{
	if(!isset($_REQUEST["category"]) and $global_settings["depositphotos_category"] != -1)
	{
		$url .= '&dp_search_categories=' . $global_settings["depositphotos_category"];
	}
}



//License
if(@$_REQUEST["license"] == "commercial")
{
	$url .= '&dp_search_editorial=0';
}
if(@$_REQUEST["license"] == "editorial")
{
	$url .= '&dp_search_editorial=1';
}


//Language
if(@$_REQUEST["language"] != "")
{
	$url .= '&dp_lang=' . result($_REQUEST["language"]);
}

//Orientation
if(@$_REQUEST["orientation"] != "" and @$_REQUEST["orientation"] != "-1")
{
	$url .= '&dp_search_orientation=' . result($_REQUEST["orientation"]);
}


//Color
if(@$_REQUEST["color"] != "")
{
	$url .= '&dp_search_color=' . result($_REQUEST["color"]);
}





//Age
if(@$_REQUEST["age"] != "")
{
	$url .= '&dp_search_age=' . result($_REQUEST["age"]);
}

//Gender
if(@$_REQUEST["gender"] != "")
{
	$url .= '&dp_search_gender=' . result($_REQUEST["gender"]);
}

//Ethnicity
if(@$_REQUEST["ethnicity"] != "")
{
	$url .= '&dp_search_race=' . result($_REQUEST["ethnicity"]);
}

//People number
if(@$_REQUEST["people_number"] != "")
{
	$url .= '&dp_search_quantity=' . result($_REQUEST["people_number"]);
}




//echo($url."<br><br>");

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

$data = curl_exec($ch); 
if (!curl_errno($ch)) 
{
    $results=json_decode($data);
    //var_dump($results);
    $n = 0;
    if(isset($results->result))
    {
		foreach ($results->result as $key => $value) 
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
			
			$itembox=str_replace("{ITEM_VIEWED}",@$value->views,$itembox);
			$itembox=str_replace("{DOWNLOADS}",@$value->downloads,$itembox);
			
			//Image
			if(@$value->type == "image" or @$value->type == "vector")
			{			
				$itembox = str_replace("{ITEM_TITLE_FULL}",@$value->title,$itembox);
				
				$itembox = str_replace("{ITEM_IMG}",@$value->thumb_large,$itembox);
				
				$lightbox_width=@$value->width;
				$lightbox_height=@$value->height;
				$lightbox_url=@$value->thumb_max;
				
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
				
				$lightbox_hover="onMouseover=\"lightboxon('".$lightbox_url."',".$lightbox_width.",".$lightbox_height.",event,'".site_root."','".addslashes(str_replace("'","",str_replace("\n","",str_replace("\r","",@$value->description))))	."','');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$lightbox_width.",".$lightbox_height.",event)\"";
	
				$itembox=str_replace("{ITEM_IMG2}",$lightbox_url,$itembox);
	
				$flow_width=@$value->width;
				$flow_height=@$value->height;		
			}
			//End image
			
			//Video
			if($value->type == "video")
			{	
				$itembox = str_replace("{ITEM_TITLE_FULL}",@$value->title,$itembox);
				
				$itembox = str_replace("{ITEM_IMG}",@$value->huge_thumb,$itembox);
				$itembox = str_replace("{ITEM_IMG2}",@$value->huge_thumb,$itembox);
				
				$video_width=$global_settings["video_width"];
				$video_height=round($global_settings["video_width"]*@$value->height/@$value->width);
				
				$lightbox_hover="onMouseover=\"lightboxon5('".$value->mp4."',".$video_width.",".$video_height.",event,'".site_root."');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$video_width.",".$video_height.",event)\"";
	
				$flow_width=$global_settings["width_flow"];
				$flow_height=round($global_settings["width_flow"]*@$value->height/@$value->width);
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
			
			
			$itembox=str_replace("{ITEM_DESCRIPTION}",$value->description,$itembox);
			$itembox=str_replace("{ITEM_KEYWORDS}","",$itembox);	
			$itembox=str_replace("{TEMPLATE_ROOT}",site_root."/".$site_template_url,$itembox);
			$itembox=str_replace("{SITE_ROOT}",site_root,$itembox);
			
			
			if($global_settings["depositphotos_pages"])
			{
				$itembox=str_replace("{ITEM_URL}",get_stock_page_url("depositphotos",@$value->id,@$value->title,str_replace("video","videos",@$value->type)),$itembox);
			}
			else
			{
				$aff_url=get_stock_affiliate_url("depositphotos",@$value->id,@$value->type);
				
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
	
	$stock_result_count = @$results->count;
}
else
{
	echo(word_lang("Error. The script cannot connect to API."));
}

curl_close($ch); 

?>