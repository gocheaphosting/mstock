<?
if(!defined("site_root")){exit();}
?>


<script type="text/javascript" src="<?=site_root?>/inc/jquery.lightbox-0.5.js"></script>
<link rel="stylesheet" type="text/css" href="<?=site_root?>/inc/jquery.lightbox-0.5.css" media="screen" />


    <script type="text/javascript">
    $(function() {
        $('#gallery a').lightBox();
    });
    </script>

<?

$sql="update vector set viewed=viewed+1 where id_parent=".(int)$id_parent;
$db->execute($sql);

$boxcontent=word_lang("Oops! The file was removed.");

if (!$smarty->isCached('item.tpl',cache_id('item')) or $site_cache_item<0)
{
	$sql="select id_parent,title,data,published,description,featured,keywords,author,viewed,userid,free,downloaded,rating,model,flash_width,flash_height,flash_version,script_version,server1,category2,category3,google_x,google_y,url,rights_managed,vote_like,vote_dislike,contacts,exclusive,content_type from vector where published=1 and id_parent=".(int)$id_parent;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$translate_results=translate_publication($rs->row["id_parent"],$rs->row["title"],$rs->row["description"],$rs->row["keywords"]);
		
		$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."item_vector.tpl");
		$folder=$rs->row["id_parent"];
		$kk=0;
		$fl=false;

		$remote_files=array();
		$remote_previews=array();
		$flag_storage=false;
		
		if($global_settings["amazon"] or $global_settings["rackspace"])
		{
			$sql="select filename1,filename2,url,item_id,filesize,width,height from filestorage_files where id_parent=".$rs->row["id_parent"];
			$ds->open($sql);
			while(!$ds->eof)
			{
				if($ds->row["item_id"]!=0)
				{
					$remote_files[$ds->row["filename1"]]=$ds->row["filesize"];
				}
				else
				{
					$remote_previews[$ds->row["filename1"]]=$ds->row["url"]."/".$ds->row["filename2"];
					if(preg_match("/thumb2/",$ds->row["filename1"]))
					{
						$remote_thumb_width=$ds->row["width"];
						$remote_thumb_height=$ds->row["height"];
					}
				}
				
				$flag_storage=true;
				$ds->movenext();
			}
		}	

		//Show categories
		$boxcontent=str_replace("{WORD_CATEGORY}",word_lang("category"),$boxcontent);
		$boxcontent=str_replace("{CATEGORY}",show_category($rs->row["id_parent"],$rs->row["category2"],$rs->row["category3"]),$boxcontent);
		
		
		$boxcontent=str_replace("{TITLE}",$translate_results["title"],$boxcontent);
		$boxcontent=str_replace("{URL}",surl.site_root.$rs->row["url"],$boxcontent);
		$boxcontent=str_replace("{WORD_DIMENSIONS}",word_lang("files"),$boxcontent);
		$boxcontent=str_replace("{WORD_ID}","ID",$boxcontent);
		$boxcontent=str_replace("{WORD_PUBLISHED}",word_lang("published"),$boxcontent);
		$boxcontent=str_replace("{PUBLISHED}",date(date_short,$rs->row["data"]),$boxcontent);
		$boxcontent=str_replace("{WORD_RATING}",word_lang("rating"),$boxcontent);
		$boxcontent=str_replace("{WORD_FLASH_VERSION}",word_lang("flash version"),$boxcontent);
		$boxcontent=str_replace("{WORD_SCRIPT_VERSION}",word_lang("flash script version"),$boxcontent);
		
		
		$boxcontent=str_replace("{WORD_LICENSE}",word_lang("license"),$boxcontent);
		
		$boxcontent=str_replace("{LICENSE}",site_root."/members/license.php",$boxcontent);
		$boxcontent=str_replace("{PATH}",@$path,$boxcontent);

		//Show rating
		show_rating($id_parent,$rs->row["rating"]);
		
		$boxcontent=str_replace("{WORD_DOWNLOADS}",word_lang("downloads"),$boxcontent);
		$boxcontent=str_replace("{DOWNLOADS}",strval($rs->row["downloaded"]),$boxcontent);
		
		$boxcontent=str_replace("{EXCLUSIVE}",word_lang("Exclusive price. The file will be removed from the stock after the purchase"),$boxcontent);
				
		$boxcontent=format_layout($boxcontent,"exclusive",$rs->row["exclusive"]);
		
		
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
		

		//Show community tools
		show_community();
		
		
		//Show author
		show_author($rs->row["author"]);
		
		
		//Show keywords
		$keywords=array();
		$titles=explode(" ",remove_words($translate_results["title"]));
		show_keywords($id_parent,"vector");
		
		
		
		//Content type
		$boxcontent=str_replace("{CONTENT_TYPE}","<a href='".site_root."/index.php?content_type=".$rs->row["content_type"]."'>".$rs->row["content_type"]."</a>",$boxcontent);
		
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


		$boxcontent=str_replace("{WORD_PREVIEW}",word_lang("preview"),$boxcontent);
		
		//Lightbox previews
		$preview_items="";
		
		//Gallery slideshow previews
		$preview_items2="";
		$afiles=array();
		
		
		//Preview screenshots
		if(!$flag_storage)
		{
			$dir = opendir ($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$folder);
			while ($file = readdir ($dir)) 
			{
				if($file <> "." && $file <> "..")
				{
					if(preg_match("/thumbs[0-9]+/",$file)) 
					{
						if(preg_match("/.jpg$|.jpeg$|.png$|.gif$/i",$file)) 
						{
							$kk=explode("thumbs",$file);
							if(count($kk)>1)
							{
								$afiles[(int)$kk[1]]=$file;
							}
						}
					}
				}
			}
			closedir ($dir);
			ksort ($afiles);
			reset ($afiles);
	
			for($k=0;$k<count($afiles);$k++)
			{
				if(isset($afiles[$k]))
				{
					$file=$afiles[$k];
					$thumbz=str_replace("thumbs","thumbz",$file);
					$thumbz_link="";
					if(file_exists($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$folder."/".$thumbz))
					{
						$thumbz_link="<a href=\"".site_root.server_url($rs->row["server1"])."/".$folder."/".$thumbz."\" title=\"\">";
					}
			
					$preview_items.="<td align='center'>".$thumbz_link."<img src='".site_root.server_url($rs->row["server1"])."/".$folder."/".$file."' border='0'></a></td>";
					if($k%4==0){$preview_items.="</tr><tr>";}
					
					$preview_items2.="<img src=\"".site_root.server_url($rs->row["server1"])."/".$folder."/".$thumbz."\">";					
				}
			}
		}
		else
		{
			foreach ($remote_previews as $key => $value) 
			{
				if(preg_match("/thumbs[0-9]+/",$key)) 
				{
					if(preg_match("/.jpg$|.jpeg$|.png$|.gif$/i",$key)) 
					{
						$kk=explode("thumbs",$key);
						if(count($kk)>1)
						{
							$afiles[(int)$kk[1]]=$key;
						}
					}
				}
			}
	
			for($k=1;$k<count($afiles);$k++)
			{
				if(isset($afiles[$k]))
				{
					$file=$afiles[$k];
					$thumbz=str_replace("thumbs","thumbz",$file);
					$thumbz_link="";
					if(isset($remote_previews[$thumbz]))
					{
						$thumbz_link="<a href=\"".$remote_previews[$thumbz]."\" title=\"\">";
					}
			
					$preview_items.="<td align='center'>".$thumbz_link."<img src='".$remote_previews[$file]."' border='0'></a></td>";
					if($k%4==0){$preview_items.="</tr><tr>";}
					
					$preview_items2.="<img src='".$remote_previews[$thumbz]."'>";
					
				}
			}
		}

		if($preview_items!="")
		{
			$preview_items="<div id='gallery'><table border='0' cellpadding='3' cellspacing='0'><tr>".$preview_items."</tr></table></div>";
		}
		
		
		//Disable lightbox previews
		$preview_items="";
		
		
		$boxcontent=str_replace("{PREVIEW_ITEMS}",$preview_items,$boxcontent);


		$flag_previews=false;
		if($preview_items!="")
		{
			$flag_previews=true;
		}
		$boxcontent=format_layout($boxcontent,"vector_previews",$flag_previews);
		
		
		if($preview_items2!="")
		{
			$preview_items2="<style>#galleria{height:320px;width:".$global_settings["thumb_width2"]."px}</style><div id='galleria'>".$preview_items2."</div><script> Galleria.loadTheme('".site_root."/admin/plugins/galleria/themes/classic/galleria.classic.js'); Galleria.run('#galleria');</script>";
			$boxcontent=str_replace("{IMAGE}",$preview_items2,$boxcontent);
		}
	
		//End. Preview screenshots



		//Previews swf
		$vector=show_preview($rs->row["id_parent"],"vector",3,0,$rs->row["server1"],$rs->row["id_parent"]);
		$vector_preview=show_preview($rs->row["id_parent"],"vector",3,1,$rs->row["server1"],$rs->row["id_parent"]);
				
		$boxcontent=str_replace("{SHARE_TITLE}",str_replace("\"","",str_replace(" ","+",$translate_results["title"])),$boxcontent);
		$boxcontent=str_replace("{SHARE_URL}",urlencode(surl.item_url($rs->row["id_parent"],$rs->row["url"])),$boxcontent);
		$boxcontent=str_replace("{SHARE_IMAGE}",urlencode(surl.show_preview($rs->row["id_parent"],"vector",2,1,$rs->row["server1"],$rs->row["id_parent"])),$boxcontent);
		$boxcontent=str_replace("{SHARE_DESCRIPTION}",str_replace("\"","",str_replace(" ","+",$translate_results["description"])),$boxcontent);		
		
		//Previews jpg
		if(preg_match("/icon_vector/",$vector_preview))
		{
			$vector=show_preview($rs->row["id_parent"],"vector",2,0,$rs->row["server1"],$rs->row["id_parent"]);
			$vector_preview=show_preview($rs->row["id_parent"],"vector",2,1,$rs->row["server1"],$rs->row["id_parent"]);
			if($global_settings["zoomer"] and !preg_match("/icon_vector/",$vector_preview))
			{
				$vector_preview_url=show_preview($rs->row["id_parent"],"vector",2,1,$rs->row["server1"],$rs->row["id_parent"],false);
				$vector_preview_original=str_replace("thumb2","thumb_original",$vector_preview_url);
				if($flag_storage)
				{
					if(isset($remote_previews["thumb_original.jpg"]))
					{
						$iframe_width=$remote_thumb_width;
						$iframe_height=$remote_thumb_height;
						$boxcontent=str_replace("{IMAGE}","<iframe width=".$iframe_width." height=".$iframe_height." src='".site_root."/members/content_photo_preview.php?id=".$id_parent."&width=".$iframe_width."&height=".$iframe_height."' frameborder=no scrolling=no></iframe>",$boxcontent);
					}
				}
				else
				{
					if(file_exists($_SERVER["DOCUMENT_ROOT"].$vector_preview_original))
					{
						$sz = getimagesize($_SERVER["DOCUMENT_ROOT"].$vector_preview_url); 
						$iframe_width=$sz[0];
						$iframe_height=$sz[1];
						
						$boxcontent=str_replace("{IMAGE}","<iframe width=".$iframe_width." height=".$iframe_height." src='".site_root."/members/content_photo_preview.php?id=".$id_parent."&width=".$iframe_width."&height=".$iframe_height."' frameborder=no scrolling=no></iframe>",$boxcontent);
					}
				}
			}
		}
		
		$boxcontent=str_replace("{IMAGE}",$vector,$boxcontent);
		$boxcontent=str_replace("{DOWNLOADSAMPLE}",$vector_preview,$boxcontent);
		//$boxcontent=str_replace("{DOWNLOADSAMPLE}",site_root."/members/sample.php?id=".$rs->row["id_parent"],$boxcontent);
		
		
		$flag_downloadsample=false;
		if($global_settings["download_sample"] and !preg_match("/icon_vector/",$vector_preview))
		{
			$flag_downloadsample=true;
		}
		$boxcontent=format_layout($boxcontent,"downloadsample",$flag_downloadsample);
		

		//Show next/previous navigation
		show_navigation($id_parent,"vector");
		
		
		
		//Show google map
		show_google_map($rs->row["google_x"],$rs->row["google_y"]);
		
		
		//Show related items
		$related_items=show_related_items($id_parent,"vector");


		$boxcontent=str_replace("{TEMPLATE_ROOT}",site_root."/".$site_template_url,$boxcontent);
		
		
		$sql="select id_parent,itemid from reviews where itemid=".(int)$id_parent;
		$dr->open($sql);
		$boxcontent=str_replace("{WORD_REVIEWS}",word_lang("reviews")."(".strval($dr->rc).")",$boxcontent);
		$boxcontent=str_replace("{ID}",strval($id_parent),$boxcontent);


		if($rs->row["contacts"]==0)
		{
			if($rs->row["rights_managed"]==0)
			{
				//Show prices
				$sizebox="";
				$sizebox_labels="";
				$sizeboxes=array();
				if(file_exists($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$folder))
				{
					$subscription_item=false;
					if(($global_settings["subscription"] and isset($_SESSION["people_login"]) and user_subscription($_SESSION["people_login"],$id_parent)) or $rs->row["free"]==1 or $global_settings["subscription_only"])
					{
						if($ds->row["shipped"]!=1)
						{
							$subscription_item=true;
						}
					}
				
				
					$sql="select id_parent,name from licenses order by priority";
					$dd->open($sql);
					$sizebox_labels_checked="checked";
					$sizebox_buy_checked="checked";
					while(!$dd->eof)
					{
						$flag_license=true;
				
				
						$sql="select id_parent,title from vector_types where license=".$dd->row["id_parent"]." order by priority";
						$dr->open($sql);
						while(!$dr->eof)
						{
							$sql="select id,name,url,price,shipped from items where price_id=".$dr->row["id_parent"]." and id_parent=".$id_parent." order by priority";
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
				
				
								$size="";
								if($ds->row["shipped"]==1)
								{
									$size=word_lang("shipped");
								}
								else
								{
									if(!$flag_storage and file_exists($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$folder."/".$ds->row["url"]))
									{
										$size=strval(float_opt(filesize($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$folder."/".$ds->row["url"])/(1024*1025),3))." Mb.";
									}
									else
									{
										if(isset($remote_files[$ds->row["url"]]))
										{
											$size=strval(float_opt($remote_files[$ds->row["url"]]/(1024*1024),3))." Mb.";
										}
									}
								}
			
								$bt="<input type='radio'  id='cart' name='cart' value='".$ds->row["id"]."' ".$sizebox_buy_checked.">";
								$sizebox_buy_checked="";
				
				
								if($subscription_item and $ds->row["shipped"]==1)
								{
								
								}
								else
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
								
								
									$sizeboxes[$dd->row["id_parent"]].="<tr class='tr_cart' id='tr_cart".$ds->row["id"]."'><td onClick='xcart(".$ds->row["id"].");'>".$ds->row["name"]."</td><td onClick='xcart(".$ds->row["id"].");'>".$size."</td>".$content_price."<td onClick='xcart(".$ds->row["id"].");'>".$bt."</td></tr>";
								}
								$ds->movenext();
							}
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
										
							$value="<table border='0' cellpadding='0' cellspacing='0' class='table_cart'><tr valign='top'><th width='40%'>".word_lang("title")."</th><th>".word_lang("size")."</th>".$text_price."<th>".$word_buy."</th></tr>".$value."</table>";
						}
						$sizebox.="<div name='p".$key."' id='p".$key."' style='display:".$sizebox_display."'>".$value."</div>";
						$sizebox_display="none";
					}
			
					$sizebox="<div style='margin-bottom:6px;margin-top:15px' class='price_license'><a href='".site_root."/members/license.php'>".word_lang("license").":</a></b> ".$sizebox_labels."</div>".$sizebox;
	
					if($subscription_item)
					{
						$word_cart=word_lang("download");
						if($rs->row["free"]==1)
						{
							$word_cart=word_lang("free download");
						}
						
						$sizebox.="<input id='item_button_cart' class='add_to_cart' type='button' onclick=\"add_download('vector',".$rs->row["id_parent"].",".$rs->row["server1"].")\" value='".$word_cart."'>";
					}
					else
					{
						$sizebox.="<input id='item_button_cart' class='add_to_cart' type='button' onclick=\"add_cart(0)\" value='".word_lang("add to cart")."'>";
					}	
				}
				$boxcontent=str_replace("{SIZES}",$sizebox,$boxcontent);
				//End. Show prices
			}
			else
			{
				$sizebox="<div style='margin-bottom:6px;margin-top:15px' class='price_license'><a href='".site_root."/members/license.php'>".word_lang("license").":</a></b> <input name='license' id='license1' value='1' style='margin-left:20px;margin-right:10px' onclick='apanel(1);' checked type='radio'><label for='license1'>".word_lang("rights managed")."</label></div><div name='p1' id='p1' style='display:inline'><table class='table_cart' border='0' cellpadding='0' cellspacing='0'><tbody><tr><th>".word_lang("file")."</th><th>".word_lang("size")."</th></tr>";
					
				$file_name="";
				$sql="select url from items where  id_parent=".$id_parent." and price_id=".$rs->row["rights_managed"];
				$ds->open($sql);
				if(!$ds->eof)
				{
					$file_name=$ds->row["url"];
				}
								
				if(!$flag_storage)
				{
					if($file_name!="")
					{
						if(file_exists($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$folder."/".$file_name))
						{
							$video_filesize=filesize($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$folder."/".$file_name);
						}
					}
				}
				else
				{
					$video_filesize=$remote_filesize;
				}
					
					
				$sizebox.="<tr><td>".strtoupper(get_file_info($file_name,"extention"))."</td><td>".strval(float_opt($video_filesize/(1024*1024),3))." Mb</td></tr>";
					
				$sizebox.="</tbody></table><div style='margin-top:15px'><input class='add_to_cart' type='button' value='".word_lang("calculate price")."' onClick='rights_managed(".$rs->row["id_parent"].")'></div></div>";
					
				$boxcontent=str_replace("{SIZES}",$sizebox,$boxcontent);
			}
		}
		else
		{
			$sizebox="<div style='margin-bottom:6px;margin-top:15px' class='price_license'><a href='".site_root."/members/license.php'>".word_lang("license").":</a></b> <input name='license' id='license1' value='1' style='margin-left:20px;margin-right:10px' onclick='apanel(1);' checked type='radio'><label for='license1'>".word_lang("royalty free")."</label></div><div name='p1' id='p1' style='display:inline'><table class='table_cart' border='0' cellpadding='0' cellspacing='0'><tbody><tr><th>".word_lang("file")."</th><th>".word_lang("size")."</th></tr>";
		
			$file_name="";
			$sql="select url from items where  id_parent=".$id_parent." and shipped<>1";
			$ds->open($sql);
			if(!$ds->eof)
			{
				$file_name=$ds->row["url"];
			}
							
			if(!$flag_storage)
			{
				if($file_name!="")
				{
					if(file_exists($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$folder."/".$file_name))
					{
						$video_filesize=filesize($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$folder."/".$file_name);
					}
				}
			}
			else
			{
				$video_filesize=$remote_filesize;
			}
				
			$sizebox.="<tr><td>".strtoupper(get_file_info($file_name,"extention"))."</td><td>".strval(float_opt($video_filesize/(1024*1024),3))." Mb</td></tr>";
				
			$sizebox.="</tbody></table><div style='margin-top:15px'><input class='add_to_cart' type='button' value='".word_lang("Contact us to get the price")."' onClick=\"location.href='".site_root."/contacts/index.php?file_id=".$rs->row["id_parent"]."'\"></div></div>";
				
			$boxcontent=str_replace("{SIZES}",$sizebox,$boxcontent);
		}

		//Share this
		$boxcontent=str_replace("{WORD_SHARE}",word_lang("share this"),$boxcontent);
		
		
		
		//flash script version
		$boxcontent=str_replace("{FLASH_VERSION}",$rs->row["flash_version"],$boxcontent);
		$boxcontent=str_replace("{SCRIPT_VERSION}",$rs->row["script_version"],$boxcontent);
		
		
		$flag_flash_version=false;
		if($rs->row["flash_version"]!="")
		{
			$flag_flash_version=true;
		}
		$boxcontent=format_layout($boxcontent,"flash_version",$flag_flash_version);
		
		$flag_script_version=false;
		if($rs->row["script_version"]!="")
		{
			$flag_script_version=true;
		}
		$boxcontent=format_layout($boxcontent,"script_version",$flag_script_version);
	
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