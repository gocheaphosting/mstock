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

if($search == '')
{
	$search = $global_settings["rf123_query"];
}

$url="http://api.123rf.com/rest/?method=123rf.images.search&apikey=".$global_settings["rf123_id"]."&keyword=" . urlencode($search);

if(@$_REQUEST["stock_type"] != '')
{	
	$url .= '&media_type=' . result($_REQUEST["stock_type"]);
}


//Page
$url .= '&page=' . $str . '&perpage=' . @$items;


//Sort
if(@$_REQUEST["sort"] != "" and (@$_REQUEST["sort"] == 'latest' or @$_REQUEST["sort"] == 'most_downloaded' or @$_REQUEST["sort"] == 'random'))
{
	$url .= '&orderby=' . (int)$_REQUEST["sort"];
}
else
{
	$url .= '&orderby=random';
}





//Contributor

if(@$_REQUEST["author"] != "")
{
	$url .= '&contributorid=' . result($_REQUEST["author"]);
}
else
{
	if($global_settings["rf123_contributor"] != "")
	{
		$url .= '&contributorid=' . $global_settings["rf123_contributor"];
	}
}




//Category
if(isset($_REQUEST["category"]) and $_REQUEST["category"] != -1 and (int)$_REQUEST["category"] != 0)
{
	$url .= '&category=' . (int)$_REQUEST["category"];
}
else
{
	if(!isset($_REQUEST["category"]) and $global_settings["rf123_category"] != -1 and $global_settings["rf123_category"] != 0)
	{
		$url .= '&category=' . $global_settings["rf123_category"];
	}
}




//Language
if(@$_REQUEST["language"] != "")
{
	$url .= '&language=' . result($_REQUEST["language"]);
}

//Orientation
if(@$_REQUEST["orientation"] != "" and @$_REQUEST["orientation"] != "-1")
{
	$url .= '&orientation=' . result($_REQUEST["orientation"]);
}


//Color
if(@$_REQUEST["color"] != "")
{
	$url .= '&color=' . result($_REQUEST["color"]);
}





//Age
if(@$_REQUEST["age"] != "")
{
	$url .= '&people_age=' . result($_REQUEST["age"]);
}

//Gender
if(@$_REQUEST["gender"] != "")
{
	$url .= '&people_gender=' . result($_REQUEST["gender"]);
}

//Ethnicity
if(@$_REQUEST["ethnicity"] != "")
{
	$url .= '&model_preference=' . result($_REQUEST["ethnicity"]);
}

//People number
if(@$_REQUEST["people_number"] != "")
{
	$url .= '&people_count=' . result($_REQUEST["people_number"]);
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
    $results=json_decode(json_encode(simplexml_load_string($data)));
    //var_dump($results);
    $n = 0;
    if(isset($results->images))
    {
		foreach ($results->images->image as $key => $value) 
		{
			$n++;
			
			$itembox = $boxcontent;
			$itembox = str_replace("{ITEM_TITLE}","#".@$value->{"@attributes"}->id,$itembox);
			$itembox = str_replace("{ITEM_ID}",@$value->{"@attributes"}->id,$itembox);
			
			
			
			$itembox=preg_replace('|\{if cart\}(.*)\{/if\}|Uis',"",$itembox);
			$itembox=preg_replace('|\{if cartflow\}(.*)\{/if\}|Uis',"",$itembox);
			$itembox=preg_replace('|\{if cartflow2\}(.*)\{/if\}|Uis',"",$itembox);
			$itembox=preg_replace('|\{if featured\}(.*)\{/if\}|Uis',"",$itembox);
			$itembox=preg_replace('|\{if new\}(.*)\{/if\}|Uis',"",$itembox);
			$itembox=str_replace('{CLASS2}',"",$itembox);
			
			$itembox=str_replace("{ITEM_VIEWED}","",$itembox);
			$itembox=str_replace("{DOWNLOADS}","",$itembox);
					
			$itembox = str_replace("{ITEM_TITLE_FULL}",@$value->{"@attributes"}->description,$itembox);
			
			$preview1 = 'http://images.assetsdelivery.com/thumbnails/' . @$value->{"@attributes"}->contributorid . '/' . @$value->{"@attributes"}->folder . '/' . @$value->{"@attributes"}->filename . '.jpg';
			$preview2 = 'http://images.assetsdelivery.com/compings/' . @$value->{"@attributes"}->contributorid . '/' . @$value->{"@attributes"}->folder . '/' . @$value->{"@attributes"}->filename . '.jpg';
			
			$itembox = str_replace("{ITEM_IMG}",$preview1,$itembox);
			
			$size = GetImageSize($preview2); 
			
			$lightbox_width=@$size[0];
			$lightbox_height=@$size[1];
			$lightbox_url=$preview2;
			
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
			
			$lightbox_hover="onMouseover=\"lightboxon('".$lightbox_url."',".$lightbox_width.",".$lightbox_height.",event,'".site_root."','".addslashes(str_replace("'","",str_replace("\n","",str_replace("\r","",@$value->{"@attributes"}->description))))	."','');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$lightbox_width.",".$lightbox_height.",event)\"";

			$itembox=str_replace("{ITEM_IMG2}",$lightbox_url,$itembox);

			$flow_width=@$size[0];
			$flow_height=@$size[1];		
			
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
			
			
			if($global_settings["rf123_pages"])
			{
				$itembox=str_replace("{ITEM_URL}",get_stock_page_url("123rf",@$value->{"@attributes"}->id,@$value->{"@attributes"}->description,"photo"),$itembox);
			}
			else
			{
				$aff_url=get_stock_affiliate_url("123rf",@$value->{"@attributes"}->id,"photo");
				
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
	
	$stock_result_count = @$results->images->{"@attributes"}->total;
}
else
{
	echo(word_lang("Error. The script cannot connect to API."));
}

curl_close($ch); 

?>