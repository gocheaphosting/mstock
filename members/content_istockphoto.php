<?
if(!defined("site_root")){exit();}


if(isset($istockphoto_results))
{	
	if(file_exists($DOCUMENT_ROOT."/".$site_template_url."item_stockapi.tpl"))
	{
		$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."item_stockapi.tpl");
		
		//Show only istockphoto syntax
		foreach ($mstocks as $key => $value) 
		{
			if($key == 'istockphoto')
			{
				$boxcontent = format_layout($boxcontent,$key,true);
			}
			else
			{
				$boxcontent = format_layout($boxcontent,$key,false);
			}
		}
		
		//var_dump($istockphoto_results);
		
		
		$boxcontent = str_replace("{ID}",$istockphoto_results -> id,$boxcontent);
		
		if($_GET["istockphoto_type"] == "videos")
		{
			$video_player="";
			
			if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/video_player4.tpl"))
			{
				$video_player=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/video_player4.tpl");
			}
			
			$istockphoto_video= @$istockphoto_results->display_sizes;
			$istockphoto_video2 = $istockphoto_video[0];
			
			$video_player=str_replace("{ID}",strval(@$istockphoto_results -> id),$video_player);
			$video_player=str_replace("{SITE_ROOT}",site_root,$video_player);
			$video_player=str_replace("{VIDEO_WIDTH}",$global_settings["ffmpeg_video_width"],$video_player);
			$video_player=str_replace("{VIDEO_HEIGHT}",round($global_settings["ffmpeg_video_width"]*9/16),$video_player);
			$video_player=str_replace("{PREVIEW_VIDEO}",@$istockphoto_video2->uri,$video_player);		
			$video_player=str_replace("{PREVIEW_PHOTO}",@$istockphoto_image2->uri,$video_player);
			
			$boxcontent = str_replace("{IMAGE}",$video_player,$boxcontent);
			$boxcontent = str_replace("{DOWNLOADSAMPLE}",@$istockphoto_video2->uri,$boxcontent);
			$boxcontent = str_replace("{SHARE_IMAGE}",urlencode(@$istockphoto_image2->uri),$boxcontent);
		}
		else
		{
			$boxcontent = str_replace("{IMAGE}","<img src='" . @$istockphoto_preview->uri . "' />",$boxcontent);
			$boxcontent = str_replace("{DOWNLOADSAMPLE}",@$istockphoto_preview->uri,$boxcontent);
			$boxcontent = str_replace("{SHARE_IMAGE}",urlencode(@$istockphoto_preview->uri),$boxcontent);
		}
		
		$publication_type = result($_GET["istockphoto_type"]);
		
		$boxcontent = str_replace("{TITLE}",@$istockphoto_results -> title ,$boxcontent);
		$boxcontent = str_replace("{KEYWORDS}",@$istockphoto_keywords_links,$boxcontent);
		$boxcontent = str_replace("{KEYWORDS_LITE}",@$istockphoto_keywords_links,$boxcontent);
		$boxcontent = str_replace("{DESCRIPTION}","",$boxcontent);
		$boxcontent = str_replace("{CATEGORY}",@$istockphoto_categories_links,$boxcontent);
		$boxcontent = str_replace("{AUTHOR}",'<b>' . word_lang("Artist") . ':</b> <a href="' . site_root . '/index.php?stock=istockphoto&author=' . @$istockphoto_results -> artist . '&stock_type=' . $publication_type . '" >' . @$istockphoto_results -> artist . '</a>',$boxcontent);
		$boxcontent = str_replace("{PUBLISHED}",@$istockphoto_results -> date_submitted,$boxcontent);
		$boxcontent = str_replace("{FOTOMOTO}","<script type='text/javascript' src='//widget.fotomoto.com/stores/script/".$global_settings["fotomoto_id"].".js'></script>",$boxcontent);
		$boxcontent = str_replace("{SHARE_TITLE}",str_replace("\"","",str_replace(" ","+",@$istockphoto_results -> title)),$boxcontent);
		$boxcontent = str_replace("{SHARE_URL}",urlencode(surl.get_stock_page_url("istockphoto",@$istockphoto_results -> id,@$istockphoto_results -> title,$_GET["istockphoto_type"])),$boxcontent);
		$boxcontent = str_replace("{SHARE_DESCRIPTION}","",$boxcontent);
		
		//Type
		$boxcontent = str_replace("{TYPE}",'<a href="' . site_root . '/index.php?stock=istockphoto&stock_type=' . $publication_type . '" >' . word_lang($publication_type) . '</a>',$boxcontent);

		//Published
		$boxcontent = format_layout($boxcontent,"published",true);
		
		//Category
		$boxcontent = format_layout($boxcontent,"category",true);
		
		//Model release
		if(isset($istockphoto_results -> allowed_use -> release_info))
		{
			$boxcontent = format_layout($boxcontent,"model",true);			
			$boxcontent = str_replace("{MODEL_RELEASE}",@$istockphoto_results -> allowed_use -> release_info,$boxcontent);
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"model",false);
		}
		
		//Property release
		$boxcontent = format_layout($boxcontent,"property",false);
		
		//Editorial
		if(@$istockphoto_results -> asset_family == 'editorial')
		{
			$boxcontent = format_layout($boxcontent,"editorial",true);
			$boxcontent = str_replace("{EDITORIAL}",word_lang("files for editorial use only"),$boxcontent);
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"editorial",false);
		}
		
		//Duration
		$boxcontent = format_layout($boxcontent,"duration",false);
		
		//Aspect ratio
		$boxcontent = format_layout($boxcontent,"aspect",false);
		
		//Bites per minute
		$boxcontent = format_layout($boxcontent,"bpm",false);
		
		//Album
		$boxcontent = format_layout($boxcontent,"album",false);
		
		//Vocal description
		$boxcontent = format_layout($boxcontent,"vocal_description",false);
		
		//Lyrics
		$boxcontent = format_layout($boxcontent,"lyrics",false);
		
		//Artists
		$boxcontent = format_layout($boxcontent,"artists",false);
				
		//Genres
		$boxcontent = format_layout($boxcontent,"genres",false);
		
		//Instruments
		$boxcontent = format_layout($boxcontent,"instruments",false);
		
		//Moods
		$boxcontent = format_layout($boxcontent,"moods",false);
		
		$sizes = '';
		
		//Sizes
		if($_GET["istockphoto_type"] == "photo")
		{
			$display_files = 'block';
			$display_prints = 'none';
			$checked_files = 'checked';
			$checked_prints = '';
			
			if($global_settings["istockphoto_prints"] and $global_settings["istockphoto_show"] == 2)
			{
				$display_files = 'none';
				$display_prints = 'block';
				$checked_files = '';
				$checked_prints = 'checked';
			}
			
			if($global_settings["istockphoto_files"] and $global_settings["istockphoto_prints"])
			{
				$sizes.="<input type='radio' name='license' id='files_label' style='margin:20px 10px 10px 0px'  onClick='apanel(0);' ".$checked_files."><label for='files_label' >".word_lang("files")."</label><input type='radio' name='license' id='prints_label' style='margin:20px 10px 10px 20px'  onClick='apanel(1);' ".$checked_prints."><label for='prints_label' >".word_lang("prints and products")."</label>";
			}
			
			$sizes.="<div id='prices_files' style='display:".$display_files."'><table border='0' cellpadding='0' cellspacing='0' class='table_cart'><tr valign='top'><th style='width:50%'>".word_lang("type")."</th><th>".word_lang("size")."</th><th>".word_lang("filesize")."</th></tr>";
			
			foreach ($istockphoto_results -> download_sizes as $key => $value) 
			{
				if(isset($value->media_type))
				{
					$photo_size = "";
					$photo_filesize ="";
					
					if(isset($value->width) and isset($value->height))
					{
						$photo_size = @$value->width . ' x ' . @$value->height . 'px';
					}
					
					if(isset($value->bytes))
					{
						$photo_filesize =strval(float_opt(@$value->bytes /(1024*1024),3)) . ' Mb.';
					}
					
					$size_title = explode("/",@$value->media_type);
					
					$sizes .= '<tr valign="top"><td>' . strtoupper(@$size_title[1]) . '</td><td>' . $photo_size . '</td><td>' . $photo_filesize . '</td></tr>';
				}
			}
			
			$sizes .= "</table><br>";
		
			$referal_url = @$istockphoto_results-> referral_destinations;
		
			$sizes .= "<a href='" . get_stock_affiliate_url("istockphoto",@$istockphoto_results -> id,$_GET["istockphoto_type"],@$referal_url[0]->uri,@$referal_url[1]->uri) . "' target='blank' class = 'btn btn-primary btn-lg' style='color:#ffffff;text-decoration:none'>" . word_lang("Buy on") . " " . $global_settings['istockphoto_site'] . "</a></div>";		
			
			if($global_settings["istockphoto_prints"])
			{
				$sql="select id_parent,title,price,option1,option2,option3,option4,option5,option6,option7,option8,option9,option10 from prints order by priority";
				$dr->open($sql);
				if(!$dr->eof)
				{				
					$print_buy_checked = "checked";
					
					$sizes.="<div id='prices_prints' style='display:".$display_prints."'><table border='0' cellpadding='0' cellspacing='0' class='table_cart'><tr valign='top'><th>".word_lang("title")."</th><th>".word_lang("price")."</th><th>".word_lang("buy")."</th></tr>";
					while(!$dr->eof)
					{	
						$prints_preview="";
						if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".$dr->row["id_parent"]."_1_big.jpg") or file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".$dr->row["id_parent"]."_2_big.jpg") or file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".$dr->row["id_parent"]."_3_big.jpg"))
						{
							$prints_preview="<a href='javascript:show_prints_preview(".$dr->row["id_parent"].");'>";
						}
				
						$sizes.="<tr class='tr_cart' id='tr_cart".$dr->row["id_parent"]."'><td width='60%' onClick='xprint(".$dr->row["id_parent"].");'>".$prints_preview.$dr->row["title"]."</td><td onClick='xprint(".$dr->row["id_parent"].");' ><span class='price'>".currency(1).float_opt($dr->row["price"],2,true)." ".currency(2)."</span></td><td onClick='xprint(".$dr->row["id_parent"].");'><input type='radio'  id='cartprint' name='cartprint' value='".$dr->row["id_parent"]."' ".$print_buy_checked."></td></tr>";
	
						$print_buy_checked="";
	
						$dr->movenext();
					}
				}				
				
				$sizes .= "</table><br><a href=\"javascript:prints_stock('istockphoto',".@$istockphoto_results -> id.",'".urlencode(get_stock_affiliate_url("istockphoto",@$istockphoto_results -> id,$_GET["istockphoto_type"],@$referal_url[0]->uri,@$referal_url[1]->uri))."','". urlencode(@$istockphoto_preview->uri)."','".get_stock_page_url("istockphoto",@$istockphoto_results -> id,@$istockphoto_results -> title,$_GET["istockphoto_type"])."')\" class = 'btn btn-danger btn-lg' style='color:#ffffff;text-decoration:none;'>" . word_lang("Order print") . "</a></div>";
			}
		}
		else
		{
			$sizes="<table border='0' cellpadding='0' cellspacing='0' class='table_cart'><tr valign='top'><th >".word_lang("title")."</th><th>".word_lang("description")."</th><th>".word_lang("type")."</th></tr>";
			
			foreach ($istockphoto_results -> download_sizes as $key => $value) 
			{
				if(isset($value->name))
				{
					$sizes .= '<tr valign="top"><td>' . strtoupper(@$value->name) . '</td><td>' . @$value->description . ' ' . @$value->bit_depth . '</td><td>' . @$value->content_type . '</td></tr>';
				}
			}
			
			$sizes .= "</table><br><br>";
		
			$referal_url = @$istockphoto_results-> referral_destinations;
		
			$sizes .= "<a href='" . get_stock_affiliate_url("istockphoto",@$istockphoto_results -> id,$_GET["istockphoto_type"],@$referal_url[0]->uri,@$referal_url[1]->uri) . "' target='blank' class = 'btn btn-primary btn-lg' style='color:#ffffff;text-decoration:none'>" . word_lang("Buy on") . " " . $global_settings['istockphoto_site'] . "</a>";
		}
		
		$boxcontent = str_replace("{SIZES}",$sizes,$boxcontent);
		//End. Sizes
		
		
		//Related items
		$related_items = '';
		$related_items2 = '';
		$related_count = 0;
		
		if($_GET["istockphoto_type"] == "photo")
		{		
			$url = 'https://api.gettyimages.com/v3/search/images?fields=thumb,preview,max_dimensions,title,comp&phrase='.urlencode(str_replace(" ",",",@$istockphoto_keywords_related )) . '&page=1&page_size=' . $global_settings["related_items_quantity"].'&artists='.urlencode(@$istockphoto_results -> artist);
		}
		else
		{
			$url = 'https://api.gettyimages.com/v3/search/videos?fields=thumb,preview,title,comp&phrase='.urlencode(str_replace(" ",",",@$istockphoto_keywords_related )) . '&page=1&page_size=' . $global_settings["related_items_quantity"];
		}
		//echo($url);

		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Api-Key: '.$global_settings["istockphoto_id"]));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		
		$data = curl_exec($ch); 
		if (!curl_errno($ch)) 
		{
			$istockphoto_related=json_decode($data);
			//var_dump($istockphoto_related);
			
			if(isset($istockphoto_related->images) or isset($istockphoto_related->videos))
    		{
				if($_GET["istockphoto_type"] == "photo")
				{
					$data_istock = $istockphoto_related->images;
				}
				else
				{
					$data_istock = $istockphoto_related->videos;
				}
				$related_items.="<div class=\"sc_menu\"><ul class=\"sc_menu\">";
				$item_content="";	
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."item_related.tpl"))
				{
					$item_content=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."item_related.tpl");
				}
				
				foreach ($data_istock as $key => $value) 
				{	
					$preview_title = @$value->title;
					
					if($_GET["istockphoto_type"] == "photo")
					{
						$preview_image = @$value->display_sizes;
						$preview_image2 = $preview_image[0];
						$preview_img = @$preview_image2->uri;
						
						$lightbox_width= @$value->max_dimensions->width;
						$lightbox_height=@$value->max_dimensions->height;
						$lightbox_url=@$preview_image2->uri;
						
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
	
			
						$flow_width=@$preview_image2->width;
						$flow_height=@$preview_image2->height;		
					}
					else
					{
						//Video
						$istockphoto_preview2 = @$value->display_sizes;
						$istockphoto_image2 = $istockphoto_preview2[2];
						$istockphoto_video2 = $istockphoto_preview2[0];
						
						$preview_img = @$istockphoto_image2->uri;
						
						$video_width=$global_settings["video_width"];
						$video_height=round($global_settings["video_width"]*9/16);
						
						$lightbox_hover="onMouseover=\"lightboxon_istock('".@$istockphoto_video2->uri."',".$video_width.",".$video_height.",event,'".site_root."');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$video_width.",".$video_height.",event)\"";
			
						$flow_width=$global_settings["width_flow"];
						$flow_height=round($global_settings["width_flow"]*9/16);
					}
					
					$preview_title = "#" . @$value -> id;
						
					if(@$value -> id != @$istockphoto_results -> id)
					{
						$related_items.="<li><div class='sc_menu_div' style='background:url(".$preview_img.");background-size:cover'><a href='".get_stock_page_url("istockphoto",@$value->id,@$value->title,"photo")."'><img src='".site_root."/images/e.gif' alt='".$preview_title."' border='0' ".$lightbox_hover."></a></div></li>";
						$related_count ++;
					}
					
					$item_text=$item_content;
					$item_text=str_replace("{ITEM_ID}",@$value -> id,$item_text);	
					$item_text=str_replace("{TITLE}",$preview_title,$item_text);	
					$item_text=str_replace("{DESCRIPTION}","",$item_text);
					$item_text=str_replace("{URL}",get_stock_page_url("istockphoto",@$value->id,@$value->title,"photo"),$item_text);
					$item_text=str_replace("{PREVIEW}",$preview_img,$item_text);
					$item_text=str_replace("{LIGHTBOX}",$lightbox_hover,$item_text);
					
					$str_width=" width='".$flow_width."' ";
					$str_height=" height='".$flow_height."' ";
					$str_width2="width:".$flow_width."px";
					$str_height2="height:".$flow_height."px";
					
					$item_text=str_replace("{WIDTH}",$str_width,$item_text);
					$item_text=str_replace("{HEIGHT}",$str_height,$item_text);
		
					$item_text=str_replace("{WIDTH2}",$str_width2,$item_text);
					$item_text=str_replace("{HEIGHT2}",$str_height2,$item_text);
					
					$item_text=str_replace("{SITE_ROOT}",site_root,$item_text);
						
					if(@$value -> id != @$istockphoto_results -> id)
					{	
						$related_items2.=$item_text;
					}
				}
			}
		}
		$related_items .="</ul></div>";
		
		$flag_related=false;
		if($global_settings["related_items"] and $related_count > 0)
		{
			$flag_related=true;
		}
		$boxcontent=format_layout($boxcontent,"related_items",$flag_related);
		
		$boxcontent = str_replace("{RELATED_ITEMS}",$related_items,$boxcontent);
		$boxcontent = str_replace("{RELATED_ITEMS2}",$related_items2,$boxcontent);
		//End. Related items
		
		
		
	
		$boxcontent=str_replace("{TEMPLATE_ROOT}",site_root."/".$site_template_url,$boxcontent);
		
		$boxcontent=translate_text($boxcontent);
			
		echo($boxcontent);
	}
	else
	{
		echo("Error. You should upload item_stockapi.tpl template's file.");
	}

}
else
{
	$boxcontent=word_lang("Oops! The file was removed.");
}

?>