<?
if(!defined("site_root")){exit();}


$sql="update photos set viewed=viewed+1 where id_parent=".(int)$id_parent;
$db->execute($sql);

$boxcontent=word_lang("Oops! The file was removed.");


if(!$smarty->isCached('item.tpl',cache_id('item')) or $site_cache_item<0)
{
	$sql="select id_parent,title,data,published,description,featured,keywords,author,viewed,userid,watermark,free,downloaded,rating,model,server1,category2,category3,google_x,google_y,url,editorial,rights_managed,vote_like,vote_dislike,contacts,exclusive,url_jpg,url_png,url_gif,url_raw,url_tiff,url_eps,content_type from photos where published=1 and id_parent=".(int)$id_parent;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$translate_results=translate_publication($rs->row["id_parent"],$rs->row["title"],$rs->row["description"],$rs->row["keywords"]);
		
		$photo_formats=array();
		$sql="select id,photo_type from photos_formats where enabled=1 order by id";
		$dr->open($sql);
		while(!$dr->eof)
		{
			$photo_formats[$dr->row["id"]]=$dr->row["photo_type"];
			$dr->movenext();
		}
		
		$photo_files=array();
		foreach ($photo_formats as $key => $value) 
		{
			if($rs->row["url_".$value]!="")
			{
				$photo_files[$value]=$rs->row["url_".$value];
				$image_width[$value]=0;
				$image_height[$value]=0;
				$image_filesize[$value]=0;
			}
		}
		
		$flag_storage=false;
		$folder=$rs->row["id_parent"];
		$remote_thumb_width=0;
		$remote_thumb_height=0;

		if($global_settings["amazon"] or $global_settings["rackspace"])
		{
			$sql="select url,filename1,filename2,width,height,item_id,filesize from filestorage_files where id_parent=".$rs->row["id_parent"];
			$ds->open($sql);
			while(!$ds->eof)
			{
				$ext=strtolower(get_file_info($ds->row["filename1"],"extention"));
				if($ext=="jpeg"){$ext="jpg";}
				if($ext=="tif"){$ext="tiff";}
				
				if($ds->row["item_id"]!=0)
				{
					$image_width[$ext]=$ds->row["width"];
					$image_height[$ext]=$ds->row["height"];
					$image_filesize[$ext]=$ds->row["filesize"];
				}
				if(preg_match("/thumb2/",$ds->row["filename1"]))
				{
					$remote_thumb_width=$ds->row["width"];
					$remote_thumb_height=$ds->row["height"];
				}
				$flag_storage=true;
				$ds->movenext();
			}
		}	
	
		if(!$flag_storage)
		{
			foreach ($photo_files as $key => $value) 
			{
				if(file_exists($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$folder."/".$value))
				{
					$size = @getimagesize($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$folder."/".$value);
					$image_width[$key]=(int)$size[0];
					$image_height[$key]=(int)$size[1];
					$image_filesize[$key]=filesize($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$folder."/".$value);
				}
			}
		}
		
		$default_width=0;
		$default_height=0;
		$default_filesize=0;
		
		foreach ($photo_files as $key => $value) 
		{
			$boxcontent=str_replace("{PHOTO_WIDTH}",$image_width[$key],$boxcontent);
			$boxcontent=str_replace("{PHOTO_HEIGHT}",$image_height[$key],$boxcontent);				
			$photo_size=strval(float_opt($image_filesize[$key]/(1024*1024),3))." Mb.";						
			$boxcontent=str_replace("{PHOTO_SIZE}",$photo_size,$boxcontent);
			
			if($image_width[$key]>=$image_height[$key])
			{
				if($image_width[$key]>$default_width or $default_width==0)
				{
					$default_width=$image_width[$key];
					$default_height=$image_height[$key];
					$default_filesize=$image_filesize[$key];
				}
			}
			else
			{
				if($image_height[$key]<$default_height or $default_height==0)
				{
					$default_width=$image_width[$key];
					$default_height=$image_height[$key];
					$default_filesize=$image_filesize[$key];
				}
			}
		}



		$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."item_photo.tpl");

		$kk=0;
		$fl=false;

		//Photo previews
		$preview=show_preview($rs->row["id_parent"],"photo",2,0,$rs->row["server1"],$rs->row["id_parent"]);

		$preview_url=show_preview($rs->row["id_parent"],"photo",2,1,$rs->row["server1"],$rs->row["id_parent"]);

		$preview_url2=show_preview($rs->row["id_parent"],"photo",2,1,$rs->row["server1"],$rs->row["id_parent"],false);
		
		$boxcontent=str_replace("{SHARE_TITLE}",str_replace("\"","",str_replace(" ","+",$translate_results["title"])),$boxcontent);
		$boxcontent=str_replace("{SHARE_URL}",urlencode(surl.item_url($rs->row["id_parent"],$rs->row["url"])),$boxcontent);
		$boxcontent=str_replace("{SHARE_IMAGE}",urlencode(surl.$preview_url),$boxcontent);
		$boxcontent=str_replace("{SHARE_DESCRIPTION}",str_replace("\"","",str_replace(" ","+",$translate_results["description"])),$boxcontent);


		if(!$global_settings["zoomer"] or preg_match("/icon_photo/",$preview_url))
		{
			if(!preg_match("/icon_photo/",$preview_url))
			{
				$boxcontent=str_replace("{IMAGE}","<img src='".$preview_url."' class='img-responsive'>",$boxcontent);
			}
			else
			{
				$boxcontent=str_replace("{IMAGE}","",$boxcontent);
			}
		}
		else
		{
			if(!$flag_storage)
			{
				$sz = getimagesize($_SERVER["DOCUMENT_ROOT"].$preview_url2); 
				$iframe_width=$sz[0];
				$iframe_height=$sz[1];
			}
			else
			{
				$iframe_width=$remote_thumb_width;
				$iframe_height=$remote_thumb_height;
			}

			$boxcontent=str_replace("{IMAGE}","<iframe width=".$iframe_width." height=".$iframe_height." src='".site_root."/members/content_photo_preview.php?id=".$id_parent."&width=".$iframe_width."&height=".$iframe_height."' frameborder='no' scrolling='no' class='hidden-xs hidden-sm hidden-phone'></iframe><img src='".$preview_url."' class='img-responsive hidden-md hidden-lg hidden-desktop hidden-tablet'>",$boxcontent);
		}

		//Show download sample
		$boxcontent=str_replace("{DOWNLOADSAMPLE}",$preview_url,$boxcontent);
		//$boxcontent=str_replace("{DOWNLOADSAMPLE}",site_root."/members/sample.php?id=".$rs->row["id_parent"],$boxcontent);
		
		$boxcontent=str_replace("{FOTOMOTO}","<script type='text/javascript' src='//widget.fotomoto.com/stores/script/".$global_settings["fotomoto_id"].".js'></script>",$boxcontent);

		$flag_downloadsample=false;
		if($global_settings["download_sample"] and !preg_match("/icon_photo/",$preview_url))
		{
			$flag_downloadsample=true;
		}
		$boxcontent=format_layout($boxcontent,"downloadsample",$flag_downloadsample);

		//Texts
		$boxcontent=str_replace("{TITLE}",$translate_results["title"],$boxcontent);
		$boxcontent=str_replace("{URL}",surl.site_root.$rs->row["url"],$boxcontent);
		$boxcontent=str_replace("{WORD_DIMENSIONS}",word_lang("files"),$boxcontent);
		$boxcontent=str_replace("{WORD_ID}","ID",$boxcontent);
		$boxcontent=str_replace("{WORD_PUBLISHED}",word_lang("published"),$boxcontent);
		$boxcontent=str_replace("{PUBLISHED}",date(date_short,$rs->row["data"]),$boxcontent);
		$boxcontent=str_replace("{WORD_RATING}",word_lang("rating"),$boxcontent);
		$boxcontent=str_replace("{WORD_LICENSE}",word_lang("license"),$boxcontent);
		$boxcontent=str_replace("{LICENSE}",site_root."/members/license.php",$boxcontent);
		$boxcontent=str_replace("{PATH}",@$path,$boxcontent);

		//Show category
		$boxcontent=str_replace("{WORD_CATEGORY}",word_lang("category"),$boxcontent);
		$boxcontent=str_replace("{CATEGORY}",show_category($rs->row["id_parent"],$rs->row["category2"],$rs->row["category3"]),$boxcontent);

		$boxcontent=str_replace("{EDITORIAL}",word_lang("files for editorial use only"),$boxcontent);
		$boxcontent=format_layout($boxcontent,"editorial",$rs->row["editorial"]);
		
		$boxcontent=str_replace("{EXCLUSIVE}",word_lang("Exclusive price. The file will be removed from the stock after the purchase"),$boxcontent);
		
		$boxcontent=format_layout($boxcontent,"exclusive",$rs->row["exclusive"]);

		//Show rating
		show_rating($id_parent,$rs->row["rating"]);

		$boxcontent=str_replace("{WORD_DOWNLOADS}",word_lang("downloads"),$boxcontent);
		$boxcontent=str_replace("{DOWNLOADS}",strval($rs->row["downloaded"]),$boxcontent);

		$boxcontent=str_replace("{WORD_FILE_DETAILS}",word_lang("file details"),$boxcontent);
		$boxcontent=str_replace("{WORD_VIEWED}",word_lang("viewed"),$boxcontent);
		$boxcontent=str_replace("{VIEWED}",strval($rs->row["viewed"]),$boxcontent);
		$boxcontent=str_replace("{WORD_DESCRIPTION}",word_lang("description"),$boxcontent);
		$boxcontent=str_replace("{DESCRIPTION}",str_replace("\r","<br>",str_replace("\n","<br>",$translate_results["description"])),$boxcontent);
		
		$boxcontent=str_replace("{LIKE}",(int)$rs->row["vote_like"],$boxcontent);
		$boxcontent=str_replace("{DISLIKE}",(int)$rs->row["vote_dislike"],$boxcontent);
		$boxcontent=str_replace("{WORD_AUTHOR}",word_lang("author"),$boxcontent);
		$boxcontent=str_replace("{WORD_TOOLS}",word_lang("tools"),$boxcontent);
		$boxcontent=str_replace("{WORD_PORTFOLIO}",word_lang("member portfolio"),$boxcontent);
		$boxcontent=str_replace("{WORD_MAIL}",word_lang("sitemail to user"),$boxcontent);
		$boxcontent=str_replace("{WORD_DOWNLOADSAMPLE}",word_lang("download sample"),$boxcontent);
		
		
		
		

		//Show next/previous navigation
		show_navigation($id_parent,"photos");

		//Show author
		show_author($rs->row["author"]);

		//Show community tools
		show_community();

		//Show google map
		show_google_map($rs->row["google_x"],$rs->row["google_y"]);

		//Show EXIF info
		show_exif($id_parent);

		//Show keywords
		$keywords=array();
		$titles=explode(" ",remove_words($translate_results["title"]));
		show_keywords($id_parent,"photo");

		//Show tell a friend
		$boxcontent=str_replace("{TELL_A_FRIEND_LINK}",site_root."/members/tell_a_friend.php?id_parent=".(int)$id_parent,$boxcontent);
		$boxcontent=str_replace("{WORD_TELL_A_FRIEND}",word_lang("tell a friend"),$boxcontent);

		//Show favorite buttons
		show_favorite($id_parent);

		if(isset($_SESSION["people_id"]))
		{
			$boxcontent=str_replace("{MAIL_LINK}",site_root."/members/messages_new.php?user=".$rs->row["author"],$boxcontent);
		}
		else
		{
			$boxcontent=str_replace("{MAIL_LINK}",site_root."/members/login.php",$boxcontent);
		}

		//Share this
		$boxcontent=str_replace("{WORD_SHARE}",word_lang("share this"),$boxcontent);

		//Show related items
		$related_items=show_related_items($id_parent,"photo");
		
		$boxcontent=str_replace("{TEMPLATE_ROOT}",site_root."/".$site_template_url,$boxcontent);

		$sql="select id_parent,itemid from reviews where itemid=".(int)$id_parent;
		$dr->open($sql);
		$boxcontent=str_replace("{WORD_REVIEWS}",word_lang("reviews")."(".strval($dr->rc).")",$boxcontent);
		$boxcontent=str_replace("{ID}",strval($id_parent),$boxcontent);
		
		//Content type
		$boxcontent=str_replace("{CONTENT_TYPE}","<a href='".site_root."/index.php?content_type=".$rs->row["content_type"]."'>".$rs->row["content_type"]."</a>",$boxcontent);
		
		
		//Prints
		$prints_label="";
		$prints_content="";

		if($global_settings["prints"])
		{
			$print_buy_checked="checked";
			$prints_display="none";
			if($global_settings["printsonly"])
			{
				$prints_display="block";
			}
			
			$printsid="";
			$sql="select id_parent from prints where photo=1";
			$dr->open($sql);
			while(!$dr->eof)
			{
				if($printsid!="")
				{
					$printsid.=" or ";
				}
				$printsid.="printsid=".$dr->row["id_parent"];
				$dr->movenext();
			}
			
			if($printsid!="")
			{
				$printsid=" and (".$printsid.")";
			}
			
			$sql="select id_parent,title,price,printsid from prints_items where itemid=".(int)$id_parent.$printsid." order by priority";
			$dr->open($sql);
			if(!$dr->eof)
			{	
				$prints_label="<input type='radio' name='license' id='prints_label' style='margin-left:20px;margin-right:10px'  onClick='apanel(0);'><label for='prints_label' >".word_lang("prints and products")."</label>";
		
				$prints_content.="<div name='p0' id='p0' style='display:".$prints_display."'><table border='0' cellpadding='0' cellspacing='0' class='table_cart'><tr valign='top'><th>".word_lang("title")."</th><th>".word_lang("price")."</th><th>".word_lang("buy")."</th></tr>";
				while(!$dr->eof)
				{	
					$prints_preview="";
					if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".$dr->row["printsid"]."_1_big.jpg") or file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".$dr->row["printsid"]."_2_big.jpg") or file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".$dr->row["printsid"]."_3_big.jpg"))
					{
						$prints_preview="<a href='javascript:show_prints_preview(".$dr->row["printsid"].");'>";
					}
			
					$prints_content.="<tr class='tr_cart' id='tr_cart".$dr->row["id_parent"]."'><td width='60%' onClick='xprint(".$dr->row["id_parent"].");'>".$prints_preview.$dr->row["title"]."</td><td onClick='xprint(".$dr->row["id_parent"].");' ><span class='price'>".currency(1).float_opt($dr->row["price"],2,true)." ".currency(2)."</span></td><td onClick='xprint(".$dr->row["id_parent"].");'><input type='radio'  id='cartprint' name='cartprint' value='-".$dr->row["id_parent"]."' ".$print_buy_checked."></td></tr>";

					$print_buy_checked="";

					$dr->movenext();
				}
				$prints_content.="</table><input class='add_to_cart' type='button' onclick=\"add_cart(1)\" value='".word_lang("add to cart")."'></div>";
			}
		}
		//End Prints

$photo_dpi=(int)$global_settings["resolution_dpi"];
if($photo_dpi<=0){$photo_dpi=72;}
$size_photo="px";


if($rs->row["contacts"]==0)
{
	//Show prices and prints
	if($rs->row["rights_managed"]==0)
	{
		$sizebox="";

		if(($global_settings["subscription"] and isset($_SESSION["people_login"]) and user_subscription($_SESSION["people_login"],$id_parent)) or $rs->row["free"]==1 or $global_settings["subscription_only"])
		{
			$subscription_item=true;
		}
		else
		{
			$subscription_item=false;
		}

		$sizebox_labels="";
		$sizeboxes=array();
		if(file_exists($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$folder))
		{
			$sql="select id_parent,name from licenses order by priority";
			$dd->open($sql);
			$sizebox_labels_checked="checked";
			$sizebox_buy_checked="";
			$ncount=0;
			while(!$dd->eof)
			{
				$flag_license=true;

				$sql="select id_parent,title,size,jpg,png,gif,raw,tiff,eps from sizes where license=".$dd->row["id_parent"]." order by priority";
				$dr->open($sql);
				
				while(!$dr->eof)
				{
					$sql="select id,name,url,price from items where price_id=".$dr->row["id_parent"]." and id_parent=".$id_parent." order by priority";
					$ds->open($sql);
					while(!$ds->eof)
					{
						if($flag_license)
						{
							$sizeboxes[$dd->row["id_parent"]]="";
							$sizebox_labels.="<input type='radio' name='license' id='license".$dd->row["id_parent"]."' value='".$dd->row["id_parent"]."' style='margin-left:20px;margin-right:10px'  onClick='apanel(".$dd->row["id_parent"].");' ".$sizebox_labels_checked."><label for='license".$dd->row["id_parent"]."' >".$dd->row["name"]."</label>";
							$sizebox_labels_checked="";
							$flag_license=false;
						}
						
						$photo_width=$default_width;
						$photo_height=$default_height;
						$photo_filesize=0;
						$photo_nojpg=0;
						foreach ($photo_files as $key => $value) 
						{
							if($dr->row[$key]==1)
							{
								if($key!="jpg" and $key!="gif" and $key!="png")
								{
									$photo_nojpg++;
								}
								
								if($image_width[$key]>=$image_height[$key])
								{
									if(($image_width[$key]<$photo_width or $photo_width==0) and $image_width[$key]!=0)
									{
										$photo_width=$image_width[$key];
										$photo_height=$image_height[$key];
										$photo_filesize=$image_filesize[$key];
									}
								}
								else
								{
									if(($image_height[$key]<$photo_height or $photo_height==0) and $image_height[$key]!=0)
									{
										$photo_width=$image_width[$key];
										$photo_height=$image_height[$key];
										$photo_filesize=$image_filesize[$key];
									}
								}
							}
						}
					
						if($photo_width!=0 and $photo_height!=0)
						{
							$rw=$photo_width;
							$rh=$photo_height;

										
							if($dr->row["size"]!=0)
							{
								if($rw>$rh)
								{
									$rw=$dr->row["size"];
									if($rw!=0)
									{
										$rh=round($photo_height*$rw/$photo_width);
									}
								}
								else
								{
									$rh=$dr->row["size"];
									if($rh!=0)
									{
										$rw=round($photo_width*$rh/$photo_height);
									}
								}
								$dpi=$photo_dpi;
							}
							else
							{
								$dpi=$photo_dpi;
							}
						}
					
						if($size_photo=="cm")
						{
							$rw=float_opt($rw*2.54/$dpi,1);
							$rh=float_opt($rh*2.54/$dpi,1);
						}

						$subscription_link="";
					
						if($ncount==0)
						{
							$sizebox_buy_checked="checked";
						}
						else
						{
							$sizebox_buy_checked="";
						}
					
						$bt="<input type='radio'  id='cart' name='cart' value='".$ds->row["id"]."' ".$sizebox_buy_checked.">";
						
						$flag_format=false;
						foreach ($photo_formats as $key => $value) 
						{
							if($rs->row["url_".$value]!="" and $dr->row[$value]==1)
							{
								$flag_format=true;
							}
						}
					
						if(((($photo_width>=$photo_height and $dr->row["size"]<=$photo_width) or ($photo_width<$photo_height and $dr->row["size"]<=$photo_height)) or $photo_nojpg>0) and $flag_format)
						{				
							if($ds->row["price"]!=0)
							{
								$price_text=currency(1).float_opt($ds->row["price"],2,true)." ".currency(2);
							}
							else
							{
								$price_text=word_lang("free download");
							}
							
							$content_price="<td nowrap onClick='xcart(".$ds->row["id"].");'><span class='price'>".$price_text."</span></td>";
						
							if($rs->row["free"]==1)
							{
								$content_price="";
							}
							
							$inches_string=float_opt($rw/$dpi,1)."&quot;&nbsp;x&nbsp;".float_opt($rh/$dpi,1)."&quot;&nbsp;@&nbsp;".$dpi."&nbsp;dpi";
						
							//$inches_string=float_opt($rw*2.54/$dpi,1)."cm&nbsp;x&nbsp;".float_opt($rh*2.54/$dpi,1)."cm&nbsp;@&nbsp;".$dpi."&nbsp;dpi";
							
							$formats="";
							foreach ($photo_formats as $key => $value) 
							{					
								if($dr->row[$value]==1 and $rs->row["url_".$value]!="")
								{
									if($formats!=""){$formats.=", ";}
									$formats.=strtoupper($value);
								}
							}
						
							$sizeboxes[$dd->row["id_parent"]].="<tr class='tr_cart' id='tr_cart".$ds->row["id"]."'><td onClick='xcart(".$ds->row["id"].");'>".$ds->row["name"].$subscription_link."</td><td onClick='xcart(".$ds->row["id"].");' class='hidden-xs hidden-sm'>".$formats."</td><td onClick='xcart(".$ds->row["id"].");' class='hidden-xs hidden-sm'><div class='item_pixels'>".$rw."&nbsp;x&nbsp;".$rh."&nbsp;".$size_photo."</div><div class='item_inches' style='display:none'>".$inches_string."</div></td>".$content_price."<td onClick='xcart(".$ds->row["id"].");'>".$bt."</td></tr>";
						}
						
						$ds->movenext();
					}
				$ncount++;
				$dr->movenext();
			}
			$dd->movenext();
		}

		$sizebox_display="inline";
		foreach ($sizeboxes as $key => $value)
		{
			if($value!="")
			{
				$word_buy=word_lang("buy");
				if($subscription_item)
				{
					$word_buy=word_lang("download");
				}
				
				$text_price="<th>".word_lang("price")."</th>";
				if($rs->row["free"]==1)
				{
					$text_price="";
				}
				
				
				$value="<table border='0' cellpadding='0' cellspacing='0' class='table_cart'><tr valign='top'><th width='20%'>".word_lang("title")."</th><th width='20%' class='hidden-xs hidden-sm'>".word_lang("type")."</th><th class='hidden-xs hidden-sm'><a href=\"javascript:show_size('".$key."');\" id='link_size1_".$key."' class='link_pixels'>".word_lang("pixels")."</a>&nbsp;<a href=\"javascript:show_size('".$key."');\" id='link_size2_".$key."' class='link_inches'>".word_lang("inches")."</a></th>".$text_price."<th>".$word_buy."</th></tr>".$value."</table>";
			}
			$sizebox.="<div name='p".$key."' id='p".$key."' style='display:".$sizebox_display."'>".$value."</div>";
			$sizebox_display="none";
		}

		if($global_settings["printsonly"])
		{
			$sizebox=$prints_content;
		}
		else
		{
			$sizebox="<div style='margin-bottom:6px;margin-top:15px' class='price_license'><a href='".site_root."/members/license.php'>".word_lang("license").":</a></b> ".$sizebox_labels.$prints_label."</div>".$sizebox.$prints_content;
		
			if($subscription_item)
			{
				$word_cart=word_lang("download");
				if($rs->row["free"]==1)
				{
					$word_cart=word_lang("free download");
				}
				
				$sizebox.="<input id='item_button_cart' class='add_to_cart' type='button' onclick=\"add_download('photo',".$rs->row["id_parent"].",".$rs->row["server1"].")\" value='".$word_cart."'>";
			}
			else
			{
				$sizebox.="<input id='item_button_cart' class='add_to_cart' type='button' onclick=\"add_cart(0)\" value='".word_lang("add to cart")."'><h2 style='margin-top:20px'>".word_lang("files").":</h2>";
				
				foreach ($photo_files as $key => $value) 
				{
					$sizebox.="<p><b>".strtoupper($key).":</b> ";

					if($key=="jpg" or $key=="gif" or $key=="png")
					{
						$sizebox.=$image_width[$key]."x".$image_height[$key]."px&nbsp@&nbsp;";
					}
					$sizebox.=strval(float_opt($image_filesize[$key]/(1024*1024),3))." Mb.";

					$sizebox.="</p>";
				}
			}		
		}
	}
	//End show prices and prints
	}
	else
	{
		$sizebox="<div style='margin-bottom:6px;margin-top:15px' class='price_license'><a href='".site_root."/members/license.php'>".word_lang("license").":</a></b> <input name='license' id='license1' value='1' style='margin-left:20px;margin-right:10px' onclick='apanel(1);' checked type='radio'><label for='license1'>".word_lang("rights managed")."</label>".$prints_label."</div><div name='p1' id='p1' style='display:inline'><table class='table_cart' border='0' cellpadding='0' cellspacing='0'><tbody><tr><th>".word_lang("file")."</th><th>".word_lang("pixels")."</th><th>".word_lang("size")."</th></tr>";
		
		foreach ($photo_files as $key => $value) 
		{
			$sizebox.="<tr><td>".strtoupper($key)."</td><td> ".$image_width[$key]."x".$image_height[$key]."px</td><td>".strval(float_opt($image_filesize[$key]/(1024*1024),3))." Mb</td></tr>";
		}
		
		$sizebox.="</tbody></table><div style='margin-top:15px'><input class='add_to_cart' type='button' value='".word_lang("calculate price")."' onClick='rights_managed(".$rs->row["id_parent"].")'></div></div>".$prints_content;
	}
}
else
{
		$sizebox="<div style='margin-bottom:6px;margin-top:15px' class='price_license'><a href='".site_root."/members/license.php'>".word_lang("license").":</a></b> <input name='license' id='license1' value='1' style='margin-left:20px;margin-right:10px' onclick='apanel(1);' checked type='radio'><label for='license1'>".word_lang("Contact us to get the price")."</label>".$prints_label."</div><div name='p1' id='p1' style='display:inline'><table class='table_cart' border='0' cellpadding='0' cellspacing='0'><tbody><tr><th>".word_lang("file")."</th><th>".word_lang("pixels")."</th><th>".word_lang("size")."</th></tr>";
		
		foreach ($photo_files as $key => $value) 
		{
			$sizebox.="<tr><td>".strtoupper($key)."</td><td> ".$image_width[$key]."x".$image_height[$key]."px</td><td>".strval(float_opt($image_filesize[$key]/(1024*1024),3))." Mb</td></tr>";
		}
		
		$sizebox.="</tbody></table><div style='margin-top:15px'><input class='add_to_cart' type='button' value='".word_lang("Contact us to get the price")."' onClick=\"location.href='".site_root."/contacts/index.php?file_id=".$rs->row["id_parent"]."'\"></div></div>".$prints_content;
}

	$boxcontent=str_replace("{SIZES}",$sizebox,$boxcontent);
	$boxcontent=translate_text($boxcontent);
}


}




if($site_cache_item>=0)
{
	if($site_cache_item>0)
	{
		$smarty->cache_lifetime = $site_cache_item*3600;
	}
	$smarty->assign('item', $boxcontent);
	$boxcontent=$smarty->fetch('item.tpl',cache_id('item'));
}

echo($boxcontent);
?>