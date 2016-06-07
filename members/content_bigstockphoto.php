<?
if(!defined("site_root")){exit();}

if(isset($bigstockphoto_results))
{	
	if(file_exists($DOCUMENT_ROOT."/".$site_template_url."item_stockapi.tpl"))
	{
		$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."item_stockapi.tpl");
		
		//Show only bigstockphoto syntax
		foreach ($mstocks as $key => $value) 
		{
			if($key == 'bigstockphoto')
			{
				$boxcontent = format_layout($boxcontent,$key,true);
			}
			else
			{
				$boxcontent = format_layout($boxcontent,$key,false);
			}
		}
		
		//var_dump($bigstockphoto_results);
		
		
		$boxcontent = str_replace("{ID}",$bigstockphoto_results -> id,$boxcontent);
		
		$boxcontent = str_replace("{IMAGE}","<img src='" . @$bigstockphoto_results -> preview -> url . "' />",$boxcontent);
		$boxcontent = str_replace("{DOWNLOADSAMPLE}",@$bigstockphoto_results -> preview -> url,$boxcontent);
		$boxcontent = str_replace("{SHARE_IMAGE}",urlencode(@$bigstockphoto_results -> preview -> url),$boxcontent);

		
		$publication_type = "photo";
		
		$boxcontent = str_replace("{TITLE}",@$bigstockphoto_results -> title ,$boxcontent);
		$boxcontent = str_replace("{KEYWORDS}",@$bigstockphoto_keywords_links,$boxcontent);
		$boxcontent = str_replace("{KEYWORDS_LITE}",@$bigstockphoto_keywords_links,$boxcontent);
		$boxcontent = str_replace("{DESCRIPTION}",@$bigstockphoto_results -> description,$boxcontent);
		$boxcontent = str_replace("{CATEGORY}",@$bigstockphoto_categories_links,$boxcontent);
		$boxcontent = str_replace("{AUTHOR}",'<b>' . word_lang("Contributor") . ':</b> <a href="' . site_root . '/index.php?stock=bigstockphoto&author=' . @$bigstockphoto_results -> contributor . '&stock_type=' . $publication_type . '" >' . @$bigstockphoto_results -> contributor . '</a>',$boxcontent);
		$boxcontent = str_replace("{PUBLISHED}","",$boxcontent);
		$boxcontent = str_replace("{FOTOMOTO}","<script type='text/javascript' src='//widget.fotomoto.com/stores/script/".$global_settings["fotomoto_id"].".js'></script>",$boxcontent);
		$boxcontent = str_replace("{SHARE_TITLE}",str_replace("\"","",str_replace(" ","+",@$bigstockphoto_results -> title)),$boxcontent);
		$boxcontent = str_replace("{SHARE_URL}",urlencode(surl.get_stock_page_url("bigstockphoto",@$bigstockphoto_results -> id,@$bigstockphoto_results -> title,$_GET["bigstockphoto_type"])),$boxcontent);
		$boxcontent = str_replace("{SHARE_DESCRIPTION}",str_replace("\"","",str_replace(" ","+",@$bigstockphoto_results -> description)),$boxcontent);
		
		//Type
		$boxcontent = str_replace("{TYPE}",'<a href="' . site_root . '/index.php?stock=bigstockphoto&stock_type=' . $publication_type . '" >' . word_lang($publication_type) . '</a>',$boxcontent);
		
		//Published
		$boxcontent = format_layout($boxcontent,"published",false);
		
		//Category
		$boxcontent = format_layout($boxcontent,"category",true);
		
		//Model release
		$boxcontent = format_layout($boxcontent,"model",false);
		
		//Property release
		$boxcontent = format_layout($boxcontent,"property",false);
		
		//Editorial
		$boxcontent = format_layout($boxcontent,"editorial",false);
		
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
		
		
		//Sizes
		$sizes = '';
		
		$display_files = 'block';
		$display_prints = 'none';
		$checked_files = 'checked';
		$checked_prints = '';
		
		if($global_settings["bigstockphoto_prints"] and $global_settings["bigstockphoto_show"] == 2)
		{
			$display_files = 'none';
			$display_prints = 'block';
			$checked_files = '';
			$checked_prints = 'checked';
		}
		
		if($global_settings["bigstockphoto_files"] and $global_settings["bigstockphoto_prints"])
		{
			$sizes.="<input type='radio' name='license' id='files_label' style='margin:20px 10px 10px 0px'  onClick='apanel(0);' ".$checked_files."><label for='files_label' >".word_lang("files")."</label><input type='radio' name='license' id='prints_label' style='margin:20px 10px 10px 20px'  onClick='apanel(1);' ".$checked_prints."><label for='prints_label' >".word_lang("prints and products")."</label>";
		}
		
		$sizes.="<div id='prices_files' style='display:".$display_files."'><table border='0' cellpadding='0' cellspacing='0' class='table_cart'><tr valign='top'><th style='width:70%'>".word_lang("title")."</th><th>".word_lang("size")."</th></tr>";
			
		foreach ($bigstockphoto_results -> formats as $key => $value) 
		{
			$sizes .= '<tr valign="top"><td>' . strtoupper(@$value->size_code) . '</td><td>' . @$value->width .' x ' . @$value->height . ' px</td></tr>';
		}
		
		$sizes .= "</table><br>";

		
		$sizes .= "<a href='" . get_stock_affiliate_url("bigstockphoto",@$bigstockphoto_results -> id,$_GET["bigstockphoto_type"]) . "' target='blank' class = 'btn btn-primary btn-lg' style='color:#ffffff;text-decoration:none'>" . word_lang("Buy on") . " Bigstockphoto</a></div>";
		
		if($global_settings["bigstockphoto_prints"])
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
			
			$sizes .= "</table><br><a href=\"javascript:prints_stock('bigstockphoto',".@$bigstockphoto_results -> id.",'".urlencode(get_stock_affiliate_url("bigstockphoto",@$bigstockphoto_results -> id,$_GET["bigstockphoto_type"]))."','". urlencode(@$bigstockphoto_results -> preview -> url)."','".get_stock_page_url("bigstockphoto",@$bigstockphoto_results -> id,@$bigstockphoto_results -> title,$_GET["bigstockphoto_type"])."')\" class = 'btn btn-danger btn-lg' style='color:#ffffff;text-decoration:none;'>" . word_lang("Order print") . "</a></div>";
		}
		
		$boxcontent = str_replace("{SIZES}",$sizes,$boxcontent);
		//End. Sizes
		
		
		//Related items
		$related_items = '';
		$related_items2 = '';


				
		$url='https://api.bigstockphoto.com/2/' . $global_settings["bigstockphoto_id"] . '/search/?response_detail=all&q=' . urlencode(@$bigstockphoto_results -> title) . '&page=1&limit=' . $global_settings["related_items_quantity"];
		
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		
		$data = curl_exec($ch); 
		if (!curl_errno($ch)) 
		{
			$bigstockphoto_related=json_decode($data);
			//var_dump($bigstockphoto_related);
			
			$related_items.="<div class=\"sc_menu\"><ul class=\"sc_menu\">";
			$item_content="";	
			if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."item_related.tpl"))
			{
				$item_content=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/".$site_template_url."item_related.tpl");
			}
			
			if(isset($bigstockphoto_related->data->images))
    		{
				foreach ($bigstockphoto_related->data->images as $key => $value) 
				{					
					if(@$value -> id != '' and @$value -> id != (int)$_GET["bigstockphoto"])
					{	
						$preview_title = @$value->title;
						$preview_img = @$value->preview->url;
						
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
						$lightbox_hover="onMouseover=\"lightboxon('".$lightbox_url."',".$lightbox_width.",".$lightbox_height.",event,'".site_root."','".addslashes(str_replace("'","",str_replace("\n","",str_replace("\r","",@$value->description))))	."','');\" onMouseout=\"lightboxoff();\" onMousemove=\"lightboxmove(".$lightbox_width.",".$lightbox_height.",event)\"";
	
			
						$flow_width=@$value->preview->width;
						$flow_height=@$value->preview->height;		

						
						$publication_type = "photo";
						
						$preview_title = "#" . @$value -> id;
								
						$related_items.="<li><div class='sc_menu_div' style='background:url(".$preview_img.");background-size:cover'><a href='".get_stock_page_url("bigstockphoto",@$value->id,@$value->title,$publication_type)."'><img src='".site_root."/images/e.gif' alt='".$preview_title."' border='0' ".$lightbox_hover."></a></div></li>";
					
						$item_text=$item_content;
						
						$item_text=str_replace("{ITEM_ID}",@$value -> id,$item_text);
						$item_text=str_replace("{TITLE}",$preview_title,$item_text);	
						$item_text=str_replace("{DESCRIPTION}","",$item_text);
						$item_text=str_replace("{URL}",get_stock_page_url("bigstockphoto",@$value->id,@$value->title,$publication_type),$item_text);
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