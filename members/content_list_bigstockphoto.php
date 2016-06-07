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

$url='https://api.bigstockphoto.com/2/' . $global_settings["bigstockphoto_id"] . '/search/?response_detail=all&q=' . urlencode($search);

if(@$_REQUEST["stock_type"] != '')
{	
	if($_REQUEST["stock_type"] == "photo")
	{
		$url='https://api.bigstockphoto.com/2/' . $global_settings["bigstockphoto_id"] . '/search/?response_detail=all&q=' . urlencode($search) . '&illustrations=n&vectors=n';
	}
	
	if($_REQUEST["stock_type"] == "illustration")
	{
		$url='https://api.bigstockphoto.com/2/' . $global_settings["bigstockphoto_id"] . '/search/?response_detail=all&q=' . urlencode($search) . '&illustrations=y';
	}
	
	if($_REQUEST["stock_type"] == "vector")
	{
		$url='https://api.bigstockphoto.com/2/' . $global_settings["bigstockphoto_id"] . '/search/?response_detail=all&q=' . urlencode($search) . '&vectors=y';
	}
}


//Page
$url .= '&page=' . $str . '&limit=' . @$items;



//Sort
if(@$_REQUEST["sort"] != "" and (@$_REQUEST["sort"] == 'relevant' or @$_REQUEST["sort"] == 'popular' or @$_REQUEST["sort"] == 'new'))
{
	$url .= '&order=' . result($_REQUEST["sort"]);
}
else
{
	$url .= '&order=popular';
}





//Contributor

if(@$_REQUEST["author"] != "")
{
	$url .= '&contributor=' . result($_REQUEST["author"]);
}
else
{
	if($global_settings["bigstockphoto_contributor"] != "")
	{
		$url .= '&contributor=' . $global_settings["bigstockphoto_contributor"];
	}
}




//Category
if(isset($_REQUEST["category"]) and $_REQUEST["category"] != -1)
{
	$url .= '&category=' . result($_REQUEST["category"]);
}
else
{
	if(!isset($_REQUEST["category"]) and $global_settings["bigstockphoto_category"] != -1)
	{
		$url .= '&category=' . $global_settings["bigstockphoto_category"];
	}
}




//License
if(@$_REQUEST["license"] == "commercial")
{
	$url .= '&editorial=Y';
}
if(@$_REQUEST["license"] == "editorial")
{
	$url .= '&editorial=N';
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
    if(isset($results->data->images))
    {
		foreach ($results->data->images as $key => $value) 
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
				
			$itembox = str_replace("{ITEM_TITLE_FULL}",@$value->title,$itembox);
			
			$itembox = str_replace("{ITEM_IMG}",@$value->preview->url,$itembox);
			
			$lightbox_width=@$value->preview->width;
			$lightbox_height=@$value->preview->height;
			$lightbox_url=@$value->preview->url;
			
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

			$flow_width=@$value->preview->width;
			$flow_height=@$value->preview->height;		


			
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
			$itembox=str_replace("{ITEM_KEYWORDS}",$value->keywords,$itembox);	
			$itembox=str_replace("{TEMPLATE_ROOT}",site_root."/".$site_template_url,$itembox);
			$itembox=str_replace("{SITE_ROOT}",site_root,$itembox);
			
			
			if($global_settings["bigstockphoto_pages"])
			{
				$itembox=str_replace("{ITEM_URL}",get_stock_page_url("bigstockphoto",@$value->id,@$value->title,"photo"),$itembox);
			}
			else
			{
				$aff_url=get_stock_affiliate_url("bigstockphoto",@$value->id,"photo");
				
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
	
	$stock_result_count = @$results->data->paging->total_items;
}
else
{
	echo(word_lang("Error. The script cannot connect to API."));
}

curl_close($ch); 

?>