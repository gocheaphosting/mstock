<?
if(!defined("site_root")){exit();}


if(isset($shutterstock_results))
{	
	if(file_exists($DOCUMENT_ROOT."/".$site_template_url."item_stockapi.tpl"))
	{
		$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."item_stockapi.tpl");
		
		//Show only shutterstock syntax
		foreach ($mstocks as $key => $value) 
		{
			if($key == 'shutterstock')
			{
				$boxcontent = format_layout($boxcontent,$key,true);
			}
			else
			{
				$boxcontent = format_layout($boxcontent,$key,false);
			}
		}
		
		//var_dump($shutterstock_results);
		
		
		$boxcontent = str_replace("{ID}",$shutterstock_results -> id,$boxcontent);
		
		if($_GET["shutterstock_type"] == "video")
		{
			$video_player="";
			
			if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/video_player.tpl"))
			{
				$video_player=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/video_player.tpl");
			}
			
			$video_player=str_replace("{ID}",strval(@$shutterstock_results -> id),$video_player);
			$video_player=str_replace("{SITE_ROOT}",site_root,$video_player);
			$video_player=str_replace("{VIDEO_WIDTH}",$global_settings["ffmpeg_video_width"],$video_player);
			$video_player=str_replace("{VIDEO_HEIGHT}",round($global_settings["ffmpeg_video_width"]/@$shutterstock_results -> aspect),$video_player);
			$video_player=str_replace("{PREVIEW_VIDEO}",@$shutterstock_results -> assets -> preview_mp4 -> url,$video_player);			
			$video_player=str_replace("{PREVIEW_PHOTO}",@$shutterstock_results -> assets -> thumb_jpg -> url,$video_player);
			
			$boxcontent = str_replace("{IMAGE}",$video_player,$boxcontent);
			$boxcontent = str_replace("{DOWNLOADSAMPLE}",@$shutterstock_results -> assets -> preview_mp4 -> url,$boxcontent);
			$boxcontent = str_replace("{SHARE_IMAGE}",urlencode(@$shutterstock_results -> assets -> thumb_jpg -> url),$boxcontent);
		}
		elseif($_GET["shutterstock_type"] == "audio")
		{
			$audio_player ="";
			
			if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/audio_player.tpl"))
			{
				$audio_player=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/audio_player.tpl");
			}
			
			$audio_player=str_replace("{ID}",strval(@$shutterstock_results -> id),$audio_player);
			$audio_player=str_replace("{SITE_ROOT}",site_root,$audio_player);
			$audio_player=str_replace("{PREVIEW_AUDIO}",@$shutterstock_results -> assets -> preview_mp3 -> url,$audio_player);
			
			$boxcontent = str_replace("{IMAGE}","<img src='" . @$shutterstock_results -> assets -> waveform -> url . "' style='margin-bottom:5px' />".$audio_player,$boxcontent);
			$boxcontent = str_replace("{DOWNLOADSAMPLE}",@$shutterstock_results -> assets -> preview_mp3 -> url,$boxcontent);
			$boxcontent = str_replace("{SHARE_IMAGE}",urlencode(@$shutterstock_results -> assets ->waveform -> url),$boxcontent);
		}
		else
		{
			$boxcontent = str_replace("{IMAGE}","<img src='" . @$shutterstock_results -> assets -> preview -> url . "' />",$boxcontent);
			$boxcontent = str_replace("{DOWNLOADSAMPLE}",@$shutterstock_results -> assets -> preview -> url,$boxcontent);
			$boxcontent = str_replace("{SHARE_IMAGE}",urlencode(@$shutterstock_results -> assets -> preview -> url),$boxcontent);
		}
		
		$publication_type = 'photo';
		if(isset($shutterstock_results -> image_type))
		{
			$publication_type = @$shutterstock_results -> image_type;
		}
		else
		{
			$publication_type = str_replace("audio","music",str_replace("video","videos",@$shutterstock_results -> media_type));
		}
		
		
		$boxcontent = str_replace("{TITLE}",@$shutterstock_results -> description ,$boxcontent);
		$boxcontent = str_replace("{KEYWORDS}",@$shutterstock_keywords_links,$boxcontent);
		$boxcontent = str_replace("{KEYWORDS_LITE}",@$shutterstock_keywords_links,$boxcontent);
		$boxcontent = str_replace("{DESCRIPTION}",@$shutterstock_results -> description,$boxcontent);
		$boxcontent = str_replace("{CATEGORY}",@$shutterstock_categories_links,$boxcontent);
		$boxcontent = str_replace("{AUTHOR}",'<b>' . word_lang("Contributor") . ':</b> <a href="' . site_root . '/index.php?stock=shutterstock&author=' . @$shutterstock_results -> contributor -> id . '&stock_type=' . $publication_type . '" >#' . @$shutterstock_results -> contributor -> id . '</a>',$boxcontent);
		$boxcontent = str_replace("{PUBLISHED}",@$shutterstock_results -> added_date,$boxcontent);
		$boxcontent = str_replace("{FOTOMOTO}","<script type='text/javascript' src='//widget.fotomoto.com/stores/script/".$global_settings["fotomoto_id"].".js'></script>",$boxcontent);
		$boxcontent = str_replace("{SHARE_TITLE}",str_replace("\"","",str_replace(" ","+",@$shutterstock_results -> description)),$boxcontent);
		$boxcontent = str_replace("{SHARE_URL}",urlencode(surl.get_stock_page_url("shutterstock",@$shutterstock_results -> id,@$shutterstock_results -> description,$_GET["shutterstock_type"])),$boxcontent);
		$boxcontent = str_replace("{SHARE_DESCRIPTION}",str_replace("\"","",str_replace(" ","+",@$shutterstock_results -> description)),$boxcontent);
		
		//Type
		$boxcontent = str_replace("{TYPE}",'<a href="' . site_root . '/index.php?stock=shutterstock&stock_type=' . $publication_type . '" >' . word_lang($publication_type) . '</a>',$boxcontent);
		
		//Published
		$boxcontent = format_layout($boxcontent,"published",true);
		
		//Category
		$boxcontent = format_layout($boxcontent,"category",true);
		
		//Model release
		if(isset($shutterstock_results -> has_model_release))
		{
			$boxcontent = format_layout($boxcontent,"model",true);
			
			if(@$shutterstock_results -> has_model_release)
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
		if(isset($shutterstock_results -> has_property_release))
		{
			$boxcontent = format_layout($boxcontent,"property",true);
			
			if(@$shutterstock_results -> has_property_release)
			{
				$boxcontent = str_replace("{PROPERTY_RELEASE}",word_lang("yes"),$boxcontent);
			}
			else
			{
				$boxcontent = str_replace("{PROPERTY_RELEASE}",word_lang("no"),$boxcontent);
			}
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"property",false);
		}
		
		//Editorial
		if(isset($shutterstock_results -> is_editorial))
		{
			$boxcontent = format_layout($boxcontent,"editorial",true);
			$boxcontent = str_replace("{EDITORIAL}",word_lang("files for editorial use only"),$boxcontent);
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"editorial",false);
		}
		
		//Duration
		if(isset($shutterstock_results -> duration))
		{
			$boxcontent = format_layout($boxcontent,"duration",true);
			$boxcontent = str_replace("{DURATION}",@$shutterstock_results -> duration,$boxcontent);
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"duration",false);
		}	
		
		//Aspect ratio
		if(isset($shutterstock_results -> aspect_ratio))
		{
			$boxcontent = format_layout($boxcontent,"aspect",true);
			$boxcontent = str_replace("{ASPECT_RATIO}",@$shutterstock_results -> aspect_ratio,$boxcontent);
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"aspect",false);
		}
		
		//Bites per minute
		if(isset($shutterstock_results -> bpm))
		{
			$boxcontent = format_layout($boxcontent,"bpm",true);
			$boxcontent = str_replace("{BPM}",@$shutterstock_results -> bpm,$boxcontent);
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"bpm",false);
		}
		
		//Album
		if(isset($shutterstock_results -> album -> title) and $shutterstock_results ->  album -> title != '')
		{
			$boxcontent = format_layout($boxcontent,"album",true);
			$boxcontent = str_replace("{ALBUM}",'<a href="'. site_root . '/index.php?stock=shutterstock&stock_type=music&album=' . @$shutterstock_results ->  album -> title . '">' . @$shutterstock_results ->  album -> title . "</a>",$boxcontent);
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"album",false);
		}
		
		//Vocal description
		if(isset($shutterstock_results -> vocal_description) and $shutterstock_results ->  vocal_description != '')
		{
			$boxcontent = format_layout($boxcontent,"vocal_description",true);
			$boxcontent = str_replace("{VOCAL_DESCRIPTION}",@$shutterstock_results -> vocal_description,$boxcontent);
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"vocal_description",false);
		}
		
		//Lyrics
		if(isset($shutterstock_results -> lyrics) and $shutterstock_results ->  lyrics != '')
		{
			$boxcontent = format_layout($boxcontent,"lyrics",true);
			$boxcontent = str_replace("{LYRICS}",@$shutterstock_results -> lyrics,$boxcontent);
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"lyrics",false);
		}
		
		//Artists
		if(isset($shutterstock_results -> artists))
		{
			$shutterstock_artists = "";
			if(isset($shutterstock_results -> artists))
			{
				foreach ($shutterstock_results -> artists as $key => $value) 
				{
					if($shutterstock_artists != "")
					{
						$shutterstock_artists .= ', ';
					}
					$shutterstock_artists .= '<a href="' . site_root . '/index.php?stock=shutterstock&stock_type=music&artists=' . @$value -> name . '" >' . @$value -> name .'</a>';
				}
			}
			
			if($shutterstock_artists != '')
			{
				$boxcontent = str_replace("{ARTISTS}",$shutterstock_artists,$boxcontent);
				$boxcontent = format_layout($boxcontent,"artists",true);
			}
			else
			{
				$boxcontent = format_layout($boxcontent,"artists",false);
			}
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"artists",false);
		}
		
		
		//Genres
		if(isset($shutterstock_results -> genres))
		{
			$shutterstock_genres = "";
			if(isset($shutterstock_results -> genres))
			{
				foreach ($shutterstock_results -> genres as $key => $value) 
				{
					if($shutterstock_genres != "")
					{
						$shutterstock_genres .= ', ';
					}
					$shutterstock_genres .= '<a href="' . site_root . '/index.php?stock=shutterstock&stock_type=music&genre=' . $value . '" >' . $value .'</a>';
				}
			}
			
			if($shutterstock_genres != '')
			{
				$boxcontent = str_replace("{GENRES}",$shutterstock_genres,$boxcontent);
				$boxcontent = format_layout($boxcontent,"genres",true);
			}
			else
			{
				$boxcontent = format_layout($boxcontent,"genres",false);
			}
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"genres",false);
		}
		
		//Instruments
		if(isset($shutterstock_results -> instruments))
		{
			$shutterstock_instruments = "";
			if(isset($shutterstock_results -> instruments))
			{
				foreach ($shutterstock_results -> instruments as $key => $value) 
				{
					if($shutterstock_instruments != "")
					{
						$shutterstock_instruments .= ', ';
					}
					$shutterstock_instruments .= '<a href="' . site_root . '/index.php?stock=shutterstock&stock_type=music&instruments=' . $value . '" >' . $value .'</a>';
				}
			}
			
			if($shutterstock_instruments != '')
			{
				$boxcontent = str_replace("{INSTRUMENTS}",$shutterstock_instruments,$boxcontent);
				$boxcontent = format_layout($boxcontent,"instruments",true);
			}
			else
			{
				$boxcontent = format_layout($boxcontent,"instruments",false);
			}
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"instruments",false);
		}
		
		//Moods
		if(isset($shutterstock_results -> moods))
		{
			$shutterstock_moods = "";
			if(isset($shutterstock_results -> moods))
			{
				foreach ($shutterstock_results -> moods as $key => $value) 
				{
					if($shutterstock_moods != "")
					{
						$shutterstock_moods .= ', ';
					}
					$shutterstock_moods .= '<a href="' . site_root . '/index.php?stock=shutterstock&stock_type=music&moods=' . $value . '" >' . $value .'</a>';
				}
			}
			
			if($shutterstock_moods != '')
			{
				$boxcontent = str_replace("{MOODS}",$shutterstock_moods,$boxcontent);
				$boxcontent = format_layout($boxcontent,"moods",true);
			}
			else
			{
				$boxcontent = format_layout($boxcontent,"moods",false);
			}
		}
		else
		{
			$boxcontent = format_layout($boxcontent,"moods",false);
		}
		
		
		//Sizes
		$sizes = '';
		
		$display_files = 'block';
		$display_prints = 'none';
		$checked_files = 'checked';
		$checked_prints = '';
		
		if($global_settings["shutterstock_prints"] and $global_settings["shutterstock_show"] == 2 and $_GET["shutterstock_type"] != "audio" and $_GET["shutterstock_type"] != "video")
		{
			$display_files = 'none';
			$display_prints = 'block';
			$checked_files = '';
			$checked_prints = 'checked';
		}
		
		if($_GET["shutterstock_type"] != "video" and $_GET["shutterstock_type"] != "audio")
		{
			$th_dpi = '<th>DPI</th>';
		}
		else
		{
			$th_dpi = '';
		}
		
		if($_GET["shutterstock_type"] == "audio")
		{
			$sizes="<table border='0' cellpadding='0' cellspacing='0' class='table_cart'><tr valign='top'><th>".word_lang("title")."</th><th>".word_lang("filesize")."</th></tr>";
			
			$sizes .= '<tr valign="top"><td>' . word_lang("audio") . '</td><td>' . strval(float_opt(@$shutterstock_results -> assets -> clean_audio ->file_size /(1024*1024),3)) . ' Mb.' . '</td></tr>';
						
			$sizes .= "</table><br><br>";

		}
		else
		{
			if($global_settings["shutterstock_files"] and $global_settings["shutterstock_prints"] and $_GET["shutterstock_type"] != "audio" and $_GET["shutterstock_type"] != "video")
			{
				$sizes="<input type='radio' name='license' id='files_label' style='margin:20px 10px 10px 0px'  onClick='apanel(0);' ".$checked_files."><label for='files_label' >".word_lang("files")."</label><input type='radio' name='license' id='prints_label' style='margin:20px 10px 10px 20px'  onClick='apanel(1);' ".$checked_prints."><label for='prints_label' >".word_lang("prints and products")."</label>";
			}
			
			
			$sizes.="<div id='prices_files' style='display:".$display_files."'><table border='0' cellpadding='0' cellspacing='0' class='table_cart'><tr valign='top'><th>".word_lang("title")."</th><th>".word_lang("type")."</th><th>".word_lang("size")."</th>" . $th_dpi . "<th>".word_lang("filesize")."</th></tr>";
			
			foreach ($shutterstock_results -> assets as $key => $value) 
			{
				if(isset($value->display_name))
				{
					$photo_size = "";
					$photo_dpi = "";
					$photo_filesize ="";
					
					if(isset($value->width) and isset($value->height))
					{
						$photo_size = @$value->width . ' x ' . @$value->height . 'px';
					}
					
					if(isset($value->dpi) and $_GET["shutterstock_type"] != "video" and $_GET["shutterstock_type"] != "audio")
					{
						$photo_dpi = '<td>' . @$value->dpi . 'dpi</td>';
					}
					
					if(isset($value->file_size))
					{
						$photo_filesize =strval(float_opt(@$value->file_size /(1024*1024),3)) . ' Mb.';
					}
					
					$sizes .= '<tr valign="top"><td>' . @$value->display_name . '</td><td>' . strtoupper(@$value->format) . '</td><td>' . $photo_size . '</td>' . $photo_dpi . '<td>' . $photo_filesize . '</td></tr>';
				}
			}
			
			$sizes .= "</table><br>";
		}
		
		$sizes .= "<a href='" . get_stock_affiliate_url("shutterstock",@$shutterstock_results -> id,$_GET["shutterstock_type"]) . "' target='blank' class = 'btn btn-primary btn-lg' style='color:#ffffff;text-decoration:none'>" . word_lang("Buy on") . " Shutterstock</a></div>";
		
		if($global_settings["shutterstock_prints"] and $_GET["shutterstock_type"] != "audio" and $_GET["shutterstock_type"] != "video")
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
			
			
			$sizes .= "</table><br><a href=\"javascript:prints_stock('shutterstock',".@$shutterstock_results -> id.",'".urlencode(get_stock_affiliate_url("shutterstock",@$shutterstock_results -> id,$_GET["shutterstock_type"]))."','". urlencode(@$shutterstock_results -> assets -> preview -> url)."','".get_stock_page_url("shutterstock",@$shutterstock_results -> id,@$shutterstock_results -> description,$_GET["shutterstock_type"])."')\" class = 'btn btn-danger btn-lg' style='color:#ffffff;text-decoration:none;'>" . word_lang("Order print") . "</a></div>";
		}
		
		$boxcontent = str_replace("{SIZES}",$sizes,$boxcontent);
		//End. Sizes
		
		
		//Related items
		$related_items = '';
		$related_items2 = '';
		
		if($_GET["shutterstock_type"] != "audio")
		{
			$auth=base64_encode ($global_settings["shutterstock_id"].":".$global_settings["shutterstock_secret"]);
		
			$url = 'https://api.shutterstock.com/v2/images/' . (int)$_GET["shutterstock"]."/similar?per_page=".$global_settings["related_items_quantity"];
			
			if($_GET["shutterstock_type"] == "video")
			{
				$url = 'https://api.shutterstock.com/v2/videos/' . (int)$_GET["shutterstock"]."/similar?per_page=".$global_settings["related_items_quantity"];
			}
			
			if($_GET["shutterstock_type"] == "audio")
			{
				$url = 'https://api.shutterstock.com/v2/audio/' . (int)$_GET["shutterstock"]."/similar?per_page=".$global_settings["related_items_quantity"];
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
				$shutterstock_related=json_decode($data);
				//var_dump($shutterstock_related);
				
				if(isset($shutterstock_related->data))
				{
					$related_items.="<div class=\"sc_menu\"><ul class=\"sc_menu\">";
					$item_content="";	
					if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."item_related.tpl"))
					{
						$item_content=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."item_related.tpl");
					}
					
					foreach ($shutterstock_related->data as $key => $value) 
					{	
						//Image
						if($value->media_type == "image")
						{
							$preview_title = @$value->description;
							$preview_img = @$value->assets->preview->url;
							
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
	
				
							$flow_width=@$value->assets->preview->width;
							$flow_height=@$value->assets->preview->height;		
						}
						
						//Video
						if($value->media_type == "video")
						{
							$preview_title = @$value->description;
							$preview_img = @$value->assets->thumb_jpg->url;
							
							$video_width=$global_settings["video_width"];
							$video_height=round($global_settings["video_width"]/@$value->aspect);
							$lightbox_hover="onMouseover=\"lightboxon5('".$value->assets->preview_mp4->url."',".$video_width.",".$video_height.",event,'".site_root."');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$video_width.",".$video_height.",event)\"";
				
							$flow_width=$global_settings["width_flow"];
							if($value->aspect!=0)
							{
								$flow_height=round($global_settings["width_flow"]/@$value->aspect);
							}
						}
						
						//Audio
						if($value->media_type == "audio")
						{	
							$preview_title = @$value->title;
							$preview_img = @$value->assets->waveform->url;
							
							$video_width=$global_settings["video_width"];
							$video_height=round($global_settings["video_width"]/@$value->aspect);
							$lightbox_hover="onMouseover=\"lightboxon5('".@$value->assets->preview_mp4->url."',".$video_width.",".$video_height.",event,'".site_root."');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$video_width.",".$video_height.",event)\"";
				
							$flow_width=$global_settings["width_flow"];
							if(@$value->aspect!=0)
							{
								$flow_height=round($global_settings["width_flow"]/@$value->aspect);
							}
						}
						
						$preview_title = "#" . @$value -> id;
								
						$related_items.="<li><div class='sc_menu_div' style='background:url(".$preview_img.");background-size:cover'><a href='".get_stock_page_url("shutterstock",@$value->id,@$value->description,@$value->media_type)."'><img src='".site_root."/images/e.gif' alt='".$preview_title."' border='0' ".$lightbox_hover."></a></div></li>";
					
						$item_text=$item_content;
						$item_text=str_replace("{TITLE}",$preview_title,$item_text);	
						
						$item_text=str_replace("{ITEM_ID}",@$value -> id,$item_text);
						
						$item_text=str_replace("{DESCRIPTION}","",$item_text);
						$item_text=str_replace("{URL}",get_stock_page_url("shutterstock",@$value->id,@$value->description,@$value->media_type),$item_text);
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