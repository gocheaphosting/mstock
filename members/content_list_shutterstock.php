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


$auth=base64_encode ($global_settings["shutterstock_id"].":".$global_settings["shutterstock_secret"]);

//Create search URL:

if(isset($_REQUEST["stock_type"]))
{
	if($_REQUEST["stock_type"] == "")
	{
		$url = 'https://api.shutterstock.com/v2/images/search?query=' . urlencode($search);
	}
	
	if($_REQUEST["stock_type"] == "photo" or $_REQUEST["stock_type"] == "illustration" or $_REQUEST["stock_type"] == "vector")
	{
		$url = 'https://api.shutterstock.com/v2/images/search?query=' . urlencode($search) . '&image_type=' . result($_REQUEST["stock_type"]);
	}
	
	if($_REQUEST["stock_type"] == "videos")
	{
		$url = 'https://api.shutterstock.com/v2/videos/search?query=' . urlencode($search);
	}
	
	if($_REQUEST["stock_type"] == "music")
	{
		$url = 'https://api.shutterstock.com/v2/audio/search?query=' . urlencode($search);
	}
}
else
{
	$url = 'https://api.shutterstock.com/v2/images/search?query=' . urlencode($search);
}

//Page
$url .= '&page=' . @$str . '&per_page=' . @$items;

//Sort
if(@$_REQUEST["stock_type"] != "music")
{
	if(@$_REQUEST["sort"] != "" and (@$_REQUEST["sort"] == 'newest' or @$_REQUEST["sort"] == 'popular' or @$_REQUEST["sort"] == 'relevance' or @$_REQUEST["sort"] == 'random'))
	{
		$url .= '&sort=' . result($_REQUEST["sort"]);
	}
	else
	{
		$url .= '&sort=popular';
	}
}

//Contributor
if(@$_REQUEST["stock_type"] != "music")
{
	if(@$_REQUEST["author"] != "")
	{
		$url .= '&contributor=' . result($_REQUEST["author"]);
	}
	else
	{
		if($global_settings["shutterstock_contributor"] != "")
		{
			$url .= '&contributor=' . $global_settings["shutterstock_contributor"];
		}
	}
}

//Category
if(isset($_REQUEST["category"]) and $_REQUEST["category"] != -1)
{
	$url .= '&category=' . (int)$_REQUEST["category"];
}
else
{
	if(!isset($_REQUEST["category"]) and $global_settings["shutterstock_category"] != -1)
	{
		$url .= '&category=' . $global_settings["shutterstock_category"];
	}
}

//License
if(@$_REQUEST["license"] != "" and @$_REQUEST["stock_type"] != "music")
{
	$url .= '&license=' . result($_REQUEST["license"]);
}

//Orientation
if(@$_REQUEST["orientation"] != "" and @$_REQUEST["orientation"] != "-1" and @$_REQUEST["stock_type"] != "music" and @$_REQUEST["stock_type"] != "videos")
{
	$url .= '&orientation=' . result($_REQUEST["orientation"]);
}

//Color
if(@$_REQUEST["color"] != "" and @$_REQUEST["stock_type"] != "music" and @$_REQUEST["stock_type"] != "videos")
{
	$url .= '&color=' . result($_REQUEST["color"]);
}

//Model property release
if(isset($_REQUEST["model"]) and @$_REQUEST["stock_type"] != "music")
{
	$url .= '&people_model_released=1';
}

//Age
if(@$_REQUEST["age"] != "" and @$_REQUEST["stock_type"] != "music")
{
	$url .= '&people_age=' . result($_REQUEST["age"]);
}

//Gender
if(@$_REQUEST["gender"] != "" and @$_REQUEST["stock_type"] != "music")
{
	$url .= '&people_gender=' . result($_REQUEST["gender"]);
}

//Ethnicity
if(@$_REQUEST["ethnicity"] != "" and @$_REQUEST["stock_type"] != "music")
{
	$url .= '&people_ethnicity=' . result($_REQUEST["ethnicity"]);
}

//Ethnicity
if(@$_REQUEST["people_number"] != "" and @$_REQUEST["stock_type"] != "music")
{
	$url .= '&people_number=' . result($_REQUEST["people_number"]);
}

//Language
if(@$_REQUEST["language"] != "" and @$_REQUEST["stock_type"] != "music")
{
	$url .= '&language=' . result($_REQUEST["language"]);
}


//Aspect ratio
if(@$_REQUEST["aspect_ratio"] != "" and @$_REQUEST["stock_type"] == "videos")
{
	$url .= '&aspect_ratio=' . result($_REQUEST["aspect_ratio"]);
}

//Resolution
if(@$_REQUEST["resolution"] != "" and @$_REQUEST["stock_type"] == "videos")
{
	$url .= '&resolution=' . result($_REQUEST["resolution"]);
}

//Duration video
if(@$_REQUEST["duration_video"] != "" and @$_REQUEST["stock_type"] == "videos")
{
	$url .= '&duration_from=' . $duration_video1.'&duration_to=' . $duration_video2;
}

//Album title
if(@$_REQUEST["album_title"] != "" and @$_REQUEST["stock_type"] == "music")
{
	$url .= '&album_title=' . result($_REQUEST["album_title"]);
}

//Artists
if(@$_REQUEST["artists"] != "" and @$_REQUEST["stock_type"] == "music")
{
	$url .= '&artists=' . result($_REQUEST["artists"]);
}

//Genre
if(@$_REQUEST["genre"] != "" and @$_REQUEST["stock_type"] == "music")
{
	$url .= '&genre=' . result($_REQUEST["genre"]);
}

//Instruments
if(@$_REQUEST["instruments"] != "" and @$_REQUEST["stock_type"] == "music")
{
	$url .= '&instruments=' . result($_REQUEST["instruments"]);
}

//Lyrics
if(@$_REQUEST["lyrics"] != "" and @$_REQUEST["stock_type"] == "music")
{
	$url .= '&lyrics=' . result($_REQUEST["lyrics"]);
}

//Moods
if(@$_REQUEST["moods"] != "" and @$_REQUEST["stock_type"] == "music")
{
	$url .= '&moods=' . result($_REQUEST["moods"]);
}

//Vocal description
if(@$_REQUEST["vocal_description"] != "" and @$_REQUEST["stock_type"] == "music")
{
	$url .= '&vocal_description=' . result($_REQUEST["vocal_description"]);
}

//Instrumental
if(isset($_REQUEST["instrumental"]) and @$_REQUEST["stock_type"] == "music")
{
	$url .= '&is_instrumental=1';
}

//Duration audio
if(@$_REQUEST["duration_audio"] != "" and @$_REQUEST["stock_type"] == "music")
{
	$url .= '&duration_from=' . $duration_audio1.'&duration_to=' . $duration_audio2;
}

//BMP
if(@$_REQUEST["bmp"] != "" and @$_REQUEST["stock_type"] == "music")
{
	$bmp1=0;
	$bmp2=240;
	$bmp_mass=explode(" - ",result($_REQUEST["bmp"]));
	if(isset($bmp_mass[0]) and isset($bmp_mass[1]))
	{
		$bmp1=(int)$bmp_mass[0];
		$bmp2=(int)$bmp_mass[1];
	}
	$url .= '&bmp_from=' . $bmp1.'&bmp_to=' . $bmp2;
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
    if(isset($results->data))
    {
		foreach ($results->data as $key => $value) 
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
			if(@$value->media_type == "image")
			{			
				$itembox = str_replace("{ITEM_TITLE_FULL}",@$value->description,$itembox);
				
				$itembox = str_replace("{ITEM_IMG}",@$value->assets->small_thumb->url,$itembox);
				
				$lightbox_width=@$value->assets->preview->width;
				$lightbox_height=@$value->assets->preview->height;
				$lightbox_url=@$value->assets->preview->url;
				
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
	
				$flow_width=@$value->assets->preview->width;
				$flow_height=@$value->assets->preview->height;		
			}
			//End image
			
			//Video
			if($value->media_type == "video")
			{	
				$itembox = str_replace("{ITEM_TITLE_FULL}",@$value->description,$itembox);
				
				$itembox = str_replace("{ITEM_IMG}",@$value->assets->thumb_jpg->url,$itembox);
				$itembox = str_replace("{ITEM_IMG2}",@$value->assets->thumb_jpg->url,$itembox);
				
				$video_width=$global_settings["video_width"];
				$video_height=round($global_settings["video_width"]/$value->aspect);
				
				$lightbox_hover="onMouseover=\"lightboxon5('".$value->assets->preview_mp4->url."',".$video_width.",".$video_height.",event,'".site_root."');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$video_width.",".$video_height.",event)\"";
	
				$flow_width=$global_settings["width_flow"];
				if($value->aspect!=0)
				{
					$flow_height=round($global_settings["width_flow"]/@$value->aspect);
				}
			}
			//End. Video
			
			//Audio
			if($value->media_type == "audio")
			{	
				$itembox = str_replace("{ITEM_TITLE_FULL}",@$value->title,$itembox);
				
				$itembox = str_replace("{ITEM_IMG}",@$value->assets->waveform->url,$itembox);
				$itembox = str_replace("{ITEM_IMG2}",@$value->assets->waveform->url,$itembox);	
				
				$flow_width=$global_settings["width_flow"];			
				$flow_height=round($global_settings["width_flow"]*9/11);
				
				$lightbox_hover="onMouseover=\"lightboxon4('".@$value->assets->preview_mp3->url."',200,20,event,'".site_root."');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(200,20,event)\"";
			}
			//End. Audio
			
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
			
			
			if($global_settings["shutterstock_pages"])
			{
				$itembox=str_replace("{ITEM_URL}",get_stock_page_url("shutterstock",@$value->id,@$value->description,@$value->media_type),$itembox);
			}
			else
			{
				$aff_url=get_stock_affiliate_url("shutterstock",@$value->id,@$value->media_type);
				
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
	
	$stock_result_count = @$results->total_count;
}
else
{
	echo(word_lang("Error. The script cannot connect to API."));
}

curl_close($ch); 

?>