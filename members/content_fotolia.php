<?
if(!defined("site_root")){exit();}


if(isset($fotolia_results))
{	
	if(file_exists($DOCUMENT_ROOT."/".$site_template_url."item_stockapi.tpl"))
	{
		$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."item_stockapi.tpl");
		
		//Show only fotolia syntax
		foreach ($mstocks as $key => $value) 
		{
			if($key == 'fotolia')
			{
				$boxcontent = format_layout($boxcontent,$key,true);
			}
			else
			{
				$boxcontent = format_layout($boxcontent,$key,false);
			}
		}
		
		//var_dump($fotolia_results);
		
		$boxcontent = str_replace("{ID}",$fotolia_results -> id,$boxcontent);
		
		$fotolia_photo = str_replace("/110_","/500_",@$fotolia_results -> thumbnail_url);
		
		if($_GET["fotolia_type"] == "video")
		{
			$video_player="";
			
			if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/video_player.tpl"))
			{
				$video_player=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/video_player.tpl");
			}
			
			$video_player=str_replace("{ID}",strval(@$fotolia_results -> id),$video_player);
			$video_player=str_replace("{SITE_ROOT}",site_root,$video_player);
			$video_player=str_replace("{VIDEO_WIDTH}",$global_settings["ffmpeg_video_width"],$video_player);
			$video_player=str_replace("{VIDEO_HEIGHT}",round($global_settings["ffmpeg_video_width"]*@$fotolia_results -> thumbnail_height/@$fotolia_results -> thumbnail_width),$video_player);
			$video_player=str_replace("{PREVIEW_VIDEO}",@$fotolia_results->flv_url,$video_player);			
			$video_player=str_replace("{PREVIEW_PHOTO}",$fotolia_photo,$video_player);
			
			$boxcontent = str_replace("{IMAGE}",$video_player,$boxcontent);
			$boxcontent = str_replace("{DOWNLOADSAMPLE}",@$fotolia_results->flv_url,$boxcontent);
			$boxcontent = str_replace("{SHARE_IMAGE}",urlencode($fotolia_photo),$boxcontent);
		}
		else
		{
			
			$boxcontent = str_replace("{IMAGE}","<img src='" . $fotolia_photo . "' />",$boxcontent);
			$boxcontent = str_replace("{DOWNLOADSAMPLE}",$fotolia_photo,$boxcontent);
			$boxcontent = str_replace("{SHARE_IMAGE}",urlencode($fotolia_photo),$boxcontent);
		}
		
		if(@$fotolia_results->media_type_id == 1)
		{
			$publication_type = "photo";
		}
		if(@$fotolia_results->media_type_id == 2)
		{
			$publication_type = "illustration";
		}
		if(@$fotolia_results->media_type_id == 3)
		{
			$publication_type = "vector";
		}
		if(@$fotolia_results->media_type_id == 4)
		{
			$publication_type = "videos";
		}
		
		
		$boxcontent = str_replace("{TITLE}",@$fotolia_results -> title ,$boxcontent);
		$boxcontent = str_replace("{VIEWED}",@$fotolia_results -> nb_views ,$boxcontent);
		$boxcontent = str_replace("{DOWNLOADED}",@$fotolia_results -> nb_downloads ,$boxcontent);
		$boxcontent = str_replace("{KEYWORDS}",@$fotolia_keywords_links,$boxcontent);
		$boxcontent = str_replace("{KEYWORDS_LITE}",@$fotolia_keywords_links,$boxcontent);
		$boxcontent = str_replace("{DESCRIPTION}","",$boxcontent);
		$boxcontent = str_replace("{CATEGORY}",$fotolia_categories_links,$boxcontent);
		$boxcontent = str_replace("{AUTHOR}",'<b>' . word_lang("Contributor") . ':</b> <a href="' . site_root . '/index.php?stock=fotolia&author=' . $fotolia_results -> creator_id . '&stock_type=' . $publication_type . '" >' . $fotolia_results -> creator_name . '</a>',$boxcontent);
		
		$fotolia_date = explode(".",@$fotolia_results -> creation_date);
		
		$boxcontent = str_replace("{PUBLISHED}",$fotolia_date[0],$boxcontent);
		$boxcontent = str_replace("{FOTOMOTO}","<script type='text/javascript' src='//widget.fotomoto.com/stores/script/".$global_settings["fotomoto_id"].".js'></script>",$boxcontent);
		$boxcontent = str_replace("{SHARE_TITLE}",str_replace("\"","",str_replace(" ","+",@$fotolia_results -> title)),$boxcontent);
		$boxcontent = str_replace("{SHARE_URL}",urlencode(surl.get_stock_page_url("fotolia",@$fotolia_results -> id,@$fotolia_results -> title,$_GET["fotolia_type"])),$boxcontent);
		$boxcontent = str_replace("{SHARE_DESCRIPTION}","",$boxcontent);
		
		//Type
		$boxcontent = str_replace("{TYPE}",'<a href="' . site_root . '/index.php?stock=fotolia&stock_type=' . $publication_type . '" >' . word_lang($publication_type) . '</a>',$boxcontent);

		
		//Model release
		if(isset($fotolia_results -> has_releases))
		{
			$boxcontent = format_layout($boxcontent,"model",true);
			
			if(@$fotolia_results -> has_releases)
			{
				$boxcontent = str_replace("{MODEL_RELEASE}",word_lang("yes"),$boxcontent);
			}
			else
			{
				$boxcontent = str_replace("{MODEL_RELEASE}",word_lang("no"),$boxcontent);
			}
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"model",false);
		}
		
		//Property release
		$boxcontent = format_layout($boxcontent,"property",false);
		
		//Editorial
		$boxcontent = format_layout($boxcontent,"editorial",false);
		
		//Duration
		if(isset($fotolia_results -> duration))
		{
			$boxcontent = format_layout($boxcontent,"duration",true);
			$boxcontent = str_replace("{DURATION}",@$fotolia_results -> duration,$boxcontent);
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"duration",false);
		}	
		
		//Aspect ratio
		if(isset($fotolia_results -> aspect_ratio))
		{
			$boxcontent = format_layout($boxcontent,"aspect",true);
			$boxcontent = str_replace("{ASPECT_RATIO}",@$fotolia_results -> aspect_ratio,$boxcontent);
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"aspect",false);
		}
		
		//Published
		$boxcontent = format_layout($boxcontent,"published",true);
		
		//Category
		$boxcontent = format_layout($boxcontent,"category",true);
		
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
		
		
		//Sizes
		$sizes="";
		$fps="";
		
		$display_files = 'block';
		$display_prints = 'none';
		$checked_files = 'checked';
		$checked_prints = '';
		
		if($global_settings["fotolia_prints"] and $global_settings["fotolia_show"] == 2 and @$fotolia_results->media_type_id != 4)
		{
			$display_files = 'none';
			$display_prints = 'block';
			$checked_files = '';
			$checked_prints = 'checked';
		}
		
		if(@$fotolia_results->media_type_id == 4)
		{
			$fps="<th>".word_lang("FPS")."</th>";
		}
		
		if($global_settings["fotolia_files"] and $global_settings["fotolia_prints"] and @$fotolia_results->media_type_id != 4)
		{
			$sizes.="<input type='radio' name='license' id='files_label' style='margin:20px 10px 10px 0px'  onClick='apanel(0);' ".$checked_files."><label for='files_label' >".word_lang("files")."</label><input type='radio' name='license' id='prints_label' style='margin:20px 10px 10px 20px'  onClick='apanel(1);' ".$checked_prints."><label for='prints_label' >".word_lang("prints and products")."</label>";
		}
		
		$sizes.="<div id='prices_files' style='display:".$display_files."'><table border='0' cellpadding='0' cellspacing='0' class='table_cart'><tr valign='top'><th>".word_lang("title")."</th><th>".word_lang("size")."</th>".$fps."</tr>";
		
		foreach ($fotolia_results -> licenses_details as $key => $value) 
		{
			if(isset($value->license_name))
			{
				if(@$fotolia_results->media_type_id == 4)
				{
					$sizes .= '<tr valign="top"><td>' . $value->license_name . '</td><td>' . $value->dimensions . '</td><td>' . $value->fps . '</td></tr>';				
				}
				else
				{
					$sizes .= '<tr valign="top"><td>' . $value->license_name . '</td><td>' . $value->phrase . '</td></tr>';
				}
			}
		}
		
		$sizes .= "</table><br>";
		
		$sizes .= "<a href='" . get_stock_affiliate_url("fotolia",$fotolia_results -> id,$_GET["fotolia_type"]) . "' target='blank' class = 'btn btn-primary btn-lg' style='color:#ffffff;text-decoration:none'>" . word_lang("Buy on") . " Fotolia</a></div>";
		
		if($global_settings["fotolia_prints"] and @$fotolia_results->media_type_id != 4)
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
			
			$sizes .= "</table><br><a href=\"javascript:prints_stock('fotolia',".@$fotolia_results -> id.",'".urlencode(get_stock_affiliate_url("fotolia",@$fotolia_results -> id,$_GET["fotolia_type"],@$fotolia_results -> affiliation_link))."','". urlencode(@$fotolia_photo)."','".get_stock_page_url("fotolia",@$fotolia_results -> id,@$fotolia_results -> title,$_GET["fotolia_type"])."')\" class = 'btn btn-danger btn-lg' style='color:#ffffff;text-decoration:none;'>" . word_lang("Order print") . "</a></div>";
		}
		
		
		$boxcontent = str_replace("{SIZES}",$sizes,$boxcontent);
		//End. Sizes
		
		
		//Related items
		$related_items = '';
		$related_items2 = '';
		
		if($_GET["fotolia_type"] != "audio")
		{
			$auth=base64_encode ($global_settings["fotolia_id"].":");
			
			if(@$fotolia_results->media_type_id == 1)
			{
				$url = 'http://api.fotolia.com/Rest/1/search/getSearchResults?search_parameters[filters][content_type:photo]=1&search_parameters[similar]=' . @$fotolia_results -> id . '&search_parameters[offset]=0&search_parameters[limit]=' . $global_settings["related_items_quantity"];
			}
			if(@$fotolia_results->media_type_id == 2)
			{
				$url = 'http://api.fotolia.com/Rest/1/search/getSearchResults?search_parameters[filters][content_type:illustration]=1&search_parameters[similar]=' . @$fotolia_results -> id . '&search_parameters[offset]=0&search_parameters[limit]=' . $global_settings["related_items_quantity"];
			}
			if(@$fotolia_results->media_type_id == 3)
			{
				$url = 'http://api.fotolia.com/Rest/1/search/getSearchResults?search_parameters[filters][content_type:vector]=1&search_parameters[similar]=' . @$fotolia_results -> id . '&search_parameters[offset]=0&search_parameters[limit]=' . $global_settings["related_items_quantity"];
			}
			if(@$fotolia_results->media_type_id == 4)
			{
				$url = 'http://api.fotolia.com/Rest/1/search/getSearchResults?search_parameters[filters][content_type:video]=1&search_parameters[similar]=' . @$fotolia_results -> id . '&search_parameters[offset]=0&search_parameters[limit]=' . $global_settings["related_items_quantity"];
			}

			
			$ch = curl_init();
		
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $auth));
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			
			$data = curl_exec($ch); 
			if (!curl_errno($ch)) 
			{
				$fotolia_related=json_decode($data);
				//var_dump($fotolia_related);
				
				$related_items.="<div class=\"sc_menu\"><ul class=\"sc_menu\">";
				$item_content="";	
				if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."item_related.tpl"))
				{
					$item_content=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."item_related.tpl");
				}
				
				foreach ($fotolia_related as $key => $value) 
				{	
					if(isset($value -> id))
					{
						//Image
						if(@$value->media_type_id == 1 or @$value->media_type_id == 2 or @$value->media_type_id == 3)
						{
							$preview_title = @$value->title;
							$preview_img = @$value->thumbnail_400_url;
							
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
							$lightbox_hover="onMouseover=\"lightboxon('".$lightbox_url."',".$lightbox_width.",".$lightbox_height.",event,'".site_root."','".addslashes(str_replace("'","",str_replace("\n","",str_replace("\r","",$value->title))))	."','');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$lightbox_width.",".$lightbox_height.",event)\"";
				
							$flow_width=@$value->thumbnail_400_width;
							$flow_height=@$value->thumbnail_400_height;		
						}
						
						//Video
						if(@$value->media_type_id == 4)
						{
							$preview_title = @$value->title;
							$preview_img = @$value->thumbnail_400_url;
							
							$video_width=$global_settings["video_width"];
							$video_height=round($global_settings["video_width"]*@$value->thumbnail_400_height/@$value->thumbnail_400_width);
							
							$video_mp4 = @$value->video_data->formats->comp->url;
							$lightbox_hover="onMouseover=\"lightboxon5('".$video_mp4."',".$video_width.",".$video_height.",event,'".site_root."');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$video_width.",".$video_height.",event)\"";
				
							$flow_width=$global_settings["width_flow"];
							$flow_height=round($global_settings["width_flow"]*@$value->thumbnail_400_height/@$value->thumbnail_400_width);
						}
						
						if(@$value->media_type_id == 1)
						{
							$related_type = "photo";
						}
						if(@$value->media_type_id == 2)
						{
							$related_type = "illustration";
						}
						if(@$value->media_type_id == 3)
						{
							$related_type = "vector";
						}
						if(@$value->media_type_id == 4)
						{
							$related_type = "videos";
						}
	
						
						$preview_title = "#" . @$value -> id;
								
						$related_items.="<li><div class='sc_menu_div' style='background:url(".$preview_img.");background-size:cover'><a href='".get_stock_page_url("fotolia",$value->id,$value->title,$related_type)."'><img src='".site_root."/images/e.gif' alt='".$preview_title."' border='0' ".$lightbox_hover."></a></div></li>";
					
						$item_text=$item_content;
						$item_text=str_replace("{ITEM_ID}",@$value -> id,$item_text);	
						$item_text=str_replace("{TITLE}",$preview_title,$item_text);	
						$item_text=str_replace("{DESCRIPTION}","",$item_text);
						$item_text=str_replace("{URL}",get_stock_page_url("fotolia",$value->id,$value->title,$related_type),$item_text);
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
										
						$related_items2.=$item_text;
					}
				}
			}
		}
		
		$related_items .="</ul></div>";
		
		$flag_related=false;
		if($global_settings["related_items"] and $related_items!="")
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