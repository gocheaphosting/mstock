<?
if(!defined("site_root")){exit();}


if(!isset($_SESSION["box_shopping_cart"]) or !isset($_SESSION["box_shopping_cart_lite"]) or @$_SESSION["box_shopping_cart"]=="" or @$_SESSION["box_shopping_cart_lite"]=="" or $flag_ssl)
{
	$box_shopping_cart=word_lang("empty shopping cart");
	$box_shopping_cart_lite=word_lang("empty shopping cart");
	$script_carts="";
	$script_carts_title="";
	$script_carts_description="";
	$script_carts_price="";
	$script_carts_qty="";
	$script_carts_url="";
	$script_carts_photo="";
	$script_carts_remove="";
	$script_carts_content_id="";
	
	$script_carts2="";
	$script_carts_title2="";
	$script_carts_description2="";
	$script_carts_price2="";
	$script_carts_qty2="";
	$script_carts_url2="";
	$script_carts_photo2="";
	$script_carts_remove2="";
	$script_carts_content_id2="";

	$cart_id=shopping_cart_id();
	$total=0;
	$quantity=0;
	
	$photo_formats=array();
	$sql="select id,photo_type from photos_formats where enabled=1 order by id";
	$dr->open($sql);
	while(!$dr->eof)
	{
		$photo_formats[$dr->row["id"]]=$dr->row["photo_type"];
		$dr->movenext();
	}

	$sql="select id,item_id,prints_id,publication_id,quantity,option1_id,option1_value,option2_id,option2_value,option3_id,option3_value,option4_id,option4_value,option5_id,option5_value,option6_id,option6_value,option7_id,option7_value,option8_id,option8_value,option9_id,option9_value,option10_id,option10_value,rights_managed,printslab,stock,stock_type,stock_id,stock_url,stock_preview,stock_site_url from carts_content where id_parent=".$cart_id;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$box_shopping_cart="<table border=0 cellpadding=3 cellspacing=1 class='tborder'><tr><td class='theader'><span class='smalltext'><b>ID</b></span></td><td class=theader><span class=smalltext><b>".word_lang("item")."</b></td><td class=theader><span class=smalltext><b>".word_lang("price")."</b></td><td class=theader><span class=smalltext><b>".word_lang("qty")."</b></td></tr>";
	
		while(!$rs->eof)
		{
			if($script_carts!=""){$script_carts.=",";}
			if($script_carts_title!=""){$script_carts_title.=",";}
			if($script_carts_description!=""){$script_carts_description.=",";}
			if($script_carts_price!=""){$script_carts_price.=",";}
			if($script_carts_qty!=""){$script_carts_qty.=",";}
			if($script_carts_url!=""){$script_carts_url.=",";}
			if($script_carts_photo!=""){$script_carts_photo.=",";}
			if($script_carts_remove!=""){$script_carts_remove.=",";}
			if($script_carts_content_id!=""){$script_carts_content_id.=",";}
			
			if($script_carts2!=""){$script_carts2.="||";}
			if($script_carts_title2!=""){$script_carts_title2.="||";}
			if($script_carts_description2!=""){$script_carts_description2.="||";}
			if($script_carts_price2!=""){$script_carts_price2.="||";}
			if($script_carts_qty2!=""){$script_carts_qty2.="||";}
			if($script_carts_url2!=""){$script_carts_url2.="||";}
			if($script_carts_photo2!=""){$script_carts_photo2.="||";}
			if($script_carts_remove2!=""){$script_carts_remove2.="||";}
			if($script_carts_content_id2!=""){$script_carts_content_id2.="||";}
			
			$script_carts.=$rs->row["publication_id"];
			$script_carts_remove.="0";
			$script_carts_content_id.=$rs->row["id"];
			
			$script_carts2.=$rs->row["publication_id"];
			$script_carts_remove2.="0";
			$script_carts_content_id2.=$rs->row["id"];
		
			if($rs->row["item_id"]>0)
			{				
			//Download items
			$sql="select id,name,price,id_parent,url,shipped from items where id=".$rs->row["item_id"];
			$dr->open($sql);
			if(!$dr->eof)
			{
				$folder="";
				$server1="";				
				$fl="photos";
				$url=item_url($dr->row["id_parent"]);

				$sql="select id_parent,title,server1,url_jpg,url_png,url_gif,url_raw,url_tiff,url_eps from photos where id_parent=".(int)$dr->row["id_parent"];
				$ds->open($sql);
				if(!$ds->eof)
				{
					$translate_results=translate_publication($ds->row["id_parent"],$ds->row["title"],"","");
					$title=$translate_results["title"];
					$folder=$ds->row["id_parent"];
					
					$photo_files=array();
					foreach ($photo_formats as $key => $value) 
					{
						$photo_files[$value]=$ds->row["url_".$value];
					}

					$sql="select width,height from filestorage_files where id_parent=".$ds->row["id_parent"]." and item_id<>0";
					$dq->open($sql);
					if(!$dq->eof)
					{
						$photo_width=$dq->row["width"];
						$photo_height=$dq->row["height"];
					}
					else
					{
						if(file_exists($DOCUMENT_ROOT.server_url($ds->row["server1"])."/".$folder."/".$dr->row["url"]))
						{
							$size = getimagesize($DOCUMENT_ROOT.server_url($ds->row["server1"])."/".$folder."/".$dr->row["url"]);
							$photo_width=$size[0];
							$photo_height=$size[1];
						}
					}
					
					$rw=$photo_width;
					$rh=$photo_height;
		
					if($photo_width!=0 and $photo_height!=0)
					{
						$sql="select * from sizes where title='".$dr->row["name"]."'";
						$dq->open($sql);
						if(!$dq->eof)
						{
							if($dq->row["size"]!=0)
							{
								if($rw>$rh)
								{
									$rw=$dq->row["size"];
									if($rw!=0)
									{
										$rh=round($photo_height*$rw/$photo_width);
									}
								}
								else
								{
									$rh=$dq->row["size"];
									if($rh!=0)
									{
										$rw=round($photo_width*$rh/$photo_height);
									}
								}
							}
						}
					}
					$fl="photos";
					$server1=$ds->row["server1"];
					$preview=show_preview($ds->row["id_parent"],"photo",1,1,$ds->row["server1"],$ds->row["id_parent"]);
					$script_carts_description.="'".$rw."x".$rh."'";
					$script_carts_description2.="'".$rw."x".$rh."'";
				}

				$sql="select id_parent,title,server1 from videos where id_parent=".(int)$dr->row["id_parent"];
				$ds->open($sql);
				if(!$ds->eof)
				{
					$translate_results=translate_publication($ds->row["id_parent"],$ds->row["title"],"","");
					$title=$translate_results["title"];
					$folder=$ds->row["id_parent"];
					$fl="videos";
					$server1=$ds->row["server1"];
					$preview=show_preview($ds->row["id_parent"],"video",1,1,$ds->row["server1"],$ds->row["id_parent"]);
					$script_carts_description.="'".addslashes($dr->row["name"])."'";
					$script_carts_description2.="'".addslashes($dr->row["name"])."'";
				}

				$sql="select id_parent,title,server1 from audio where id_parent=".(int)$dr->row["id_parent"];
				$ds->open($sql);
				if(!$ds->eof)
				{
					$translate_results=translate_publication($ds->row["id_parent"],$ds->row["title"],"","");
					$title=$translate_results["title"];
					$folder=$ds->row["id_parent"];
					$fl="audio";
					$server1=$ds->row["server1"];
					$preview=show_preview($ds->row["id_parent"],"audio",1,1,$ds->row["server1"],$ds->row["id_parent"]);
					$script_carts_description.="'".addslashes($dr->row["name"])."'";					
					$script_carts_description2.="'".addslashes($dr->row["name"])."'";
				}

				$sql="select id_parent,title,server1 from vector where id_parent=".(int)$dr->row["id_parent"];
				$ds->open($sql);
				if(!$ds->eof)
				{
					$translate_results=translate_publication($ds->row["id_parent"],$ds->row["title"],"","");
					$title=$translate_results["title"];
					$folder=$ds->row["id_parent"];
					$fl="vector";
					$server1=$ds->row["server1"];
					$preview=show_preview($ds->row["id_parent"],"vector",1,1,$ds->row["server1"],$ds->row["id_parent"]);
					$script_carts_description.="'".addslashes($dr->row["name"])."'";					
					$script_carts_description2.=addslashes($dr->row["name"]);
				}
				
				$script_carts_title.="'#".$dr->row["id_parent"]." ".addslashes($title)."'";
				$script_carts_photo.="'".$preview."'";
				$script_carts_title2.="#".$dr->row["id_parent"]." ".addslashes($title);
				$script_carts_photo2.=$preview;
			}	
				
				
				
				
				if($rs->row["rights_managed"]!="")
				{
					$rights_managed_price=0;
					$rights_mass=explode("|",$rs->row["rights_managed"]);
					$rights_managed_price=$rights_mass[0];
					
					$box_shopping_cart.="<tr><td class='tcontent'><span class='smalltext'><a href='".item_url($dr->row["id_parent"])."'>".$dr->row["id_parent"]."</a></td><td class=tcontent><span class=smalltext>".word_lang("rights managed")."</td><td class=tcontent><span class=smalltext><span class='price'>".float_opt($rights_managed_price,2,true)."</span></td><td class=tcontent><span class=smalltext>".$rs->row["quantity"]."</td></tr>";
					$total+=$rights_managed_price;
					
					$script_carts_price.=$rights_managed_price;
					$script_carts_url.="'".item_url($dr->row["id_parent"])."'";
					$script_carts_price2.=$rights_managed_price;
					$script_carts_url2.=item_url($dr->row["id_parent"]);
				}
				else
				{
					$sql="select id,name,price,id_parent from items where id=".$rs->row["item_id"];
					$dr->open($sql);
					if(!$dr->eof)
					{
						$box_shopping_cart.="<tr><td class='tcontent'><span class='smalltext'><a href='".item_url($dr->row["id_parent"])."'>".$dr->row["id_parent"]."</a></td><td class=tcontent><span class=smalltext>".$dr->row["name"]."</td><td class=tcontent><span class=smalltext><span class='price'>".float_opt($dr->row["price"],2,true)."</span></td><td class=tcontent><span class=smalltext>".$rs->row["quantity"]."</td></tr>";
						$total+=$dr->row["price"]*$rs->row["quantity"];
						
						$script_carts_price.=$dr->row["price"];
						$script_carts_url.="'".item_url($dr->row["id_parent"])."'";
						$script_carts_price2.=$dr->row["price"];
						$script_carts_url2.=item_url($dr->row["id_parent"]);
					}
				}
			}
		
			if($rs->row["prints_id"]>0)
			{
				if((int)$rs->row["stock"] == 0)
				{
					if($rs->row["printslab"]<>1)
					{
						$sql="select id_parent,title,price,itemid from prints_items where id_parent=".$rs->row["prints_id"];
						$dr->open($sql);
						if(!$dr->eof)
						{
							$price=define_prints_price($dr->row["price"],$rs->row["option1_id"],$rs->row["option1_value"],$rs->row["option2_id"],$rs->row["option2_value"],$rs->row["option3_id"],$rs->row["option3_value"],$rs->row["option4_id"],$rs->row["option4_value"],$rs->row["option5_id"],$rs->row["option5_value"],$rs->row["option6_id"],$rs->row["option6_value"],$rs->row["option7_id"],$rs->row["option7_value"],$rs->row["option8_id"],$rs->row["option8_value"],$rs->row["option9_id"],$rs->row["option9_value"],$rs->row["option10_id"],$rs->row["option10_value"]);
					
							$box_shopping_cart.="<tr><td class='tcontent'><span class='smalltext'><a href='".item_url($dr->row["itemid"])."'>".$dr->row["itemid"]."</a></td><td  class=tcontent><span class=smalltext>".word_lang("prints").": ".$dr->row["title"]."</td><td  class=tcontent><span class=smalltext><span class='price'>".float_opt($price,2,true)."</span></td><td class=tcontent><span class=smalltext>".$rs->row["quantity"]."</td></tr>";
							$total+=$price*$rs->row["quantity"];
							
							$script_carts_price.=$price;
							$script_carts_url.="'".item_url($dr->row["itemid"])."'";
							$script_carts_price2.=$price;
							$script_carts_url2.=item_url($dr->row["itemid"]);
							
							$sql="select id_parent,title,server1 from photos where id_parent=".(int)$dr->row["itemid"];
							$ds->open($sql);
							if(!$ds->eof)
							{
								$translate_results=translate_publication($ds->row["id_parent"],$ds->row["title"],"","");
								$title=$translate_results["title"];
								$folder=$ds->row["id_parent"];
								$server1=$ds->row["server1"];
								$preview=show_preview($ds->row["id_parent"],"photo",1,1,$ds->row["server1"],$ds->row["id_parent"]);
							}
							
							$script_carts_title.="'#".$dr->row["itemid"]." ".addslashes($title)."'";
							$script_carts_photo.="'".$preview."'";
							$script_carts_description.="'".addslashes($dr->row["title"])."'";
							$script_carts_title2.="#".$dr->row["itemid"]." ".addslashes($title);
							$script_carts_photo2.=$preview;
							$script_carts_description2.=addslashes($dr->row["title"]);
							
						}
					}
					else
					{
						$sql="select id_parent,title,price from prints where id_parent=".$rs->row["prints_id"];
						$dr->open($sql);
						if(!$dr->eof)
						{
							$price=define_prints_price($dr->row["price"],$rs->row["option1_id"],$rs->row["option1_value"],$rs->row["option2_id"],$rs->row["option2_value"],$rs->row["option3_id"],$rs->row["option3_value"],$rs->row["option4_id"],$rs->row["option4_value"],$rs->row["option5_id"],$rs->row["option5_value"],$rs->row["option6_id"],$rs->row["option6_value"],$rs->row["option7_id"],$rs->row["option7_value"],$rs->row["option8_id"],$rs->row["option8_value"],$rs->row["option9_id"],$rs->row["option9_value"],$rs->row["option10_id"],$rs->row["option10_value"]);
					
							$box_shopping_cart.="<tr><td class='tcontent'><span class='smalltext'><a href='printslab.php'>".$rs->row["publication_id"]."</a></td><td  class=tcontent><span class=smalltext>".word_lang("prints").": ".$dr->row["title"]."</td><td  class=tcontent><span class=smalltext><span class='price'>".float_opt($price,2,true)."</span></td><td class=tcontent><span class=smalltext>".$rs->row["quantity"]."</td></tr>";
							$total+=$price*$rs->row["quantity"];
							
							$script_carts_price.=$price;
							$script_carts_price2.=$price;
							
							
							$sql="select id,title,photo,id_parent from galleries_photos where id=".(int)$dq->row["publication_id"];
							$ds->open($sql);
							if(!$ds->eof)
							{
								$title=$ds->row["title"];
								$preview=site_root."/content/galleries/".$ds->row["id_parent"]."/thumb".$ds->row["id"].".jpg";
								$url="printslab_content.php?id=".$ds->row["id_parent"];
								
								$script_carts_title.="'#".$ds->row["id"]." ".addslashes($title)."'";
								$script_carts_title2.="#".$ds->row["id"]." ".addslashes($title);
							}
							
							$script_carts_url.="'".$url."'";
							$script_carts_photo.="'".$preview."'";
							$script_carts_description.="'".addslashes($dr->row["title"])."'";
							$script_carts_url2.=$url;
							$script_carts_photo2.=$preview;
							$script_carts_description2.=addslashes($dr->row["title"]);
						}
					}
				}
				else
				{
					$sql="select id_parent,title,price from prints where id_parent=".$rs->row["prints_id"];
					$dr->open($sql);
					if(!$dr->eof)
					{
						$price=define_prints_price($dr->row["price"],$rs->row["option1_id"],$rs->row["option1_value"],$rs->row["option2_id"],$rs->row["option2_value"],$rs->row["option3_id"],$rs->row["option3_value"],$rs->row["option4_id"],$rs->row["option4_value"],$rs->row["option5_id"],$rs->row["option5_value"],$rs->row["option6_id"],$rs->row["option6_value"],$rs->row["option7_id"],$rs->row["option7_value"],$rs->row["option8_id"],$rs->row["option8_value"],$rs->row["option9_id"],$rs->row["option9_value"],$rs->row["option10_id"],$rs->row["option10_value"]);
				
						$box_shopping_cart.="<tr><td class='tcontent'><span class='smalltext'><a href='".$rs->row["stock_site_url"]."'>".@$mstocks[$rs->row["stock_type"]]." #".$rs->row["stock_id"]."</a></td><td  class=tcontent><span class=smalltext>".word_lang("prints").": ".$dr->row["title"]."</td><td  class=tcontent><span class=smalltext><span class='price'>".float_opt($price,2,true)."</span></td><td class=tcontent><span class=smalltext>".$rs->row["quantity"]."</td></tr>";
						$total+=$price*$rs->row["quantity"];
						
						$script_carts_price.=$price;
						$script_carts_price2.=$price;
						
						$title=@$mstocks[$rs->row["stock_type"]]." #".$rs->row["stock_id"];
						$preview=$rs->row["stock_preview"];
						$url=$rs->row["stock_site_url"];
						
						$script_carts_title.="'".addslashes($title)."'";
						$script_carts_title2.=addslashes($title);
						
						$script_carts_url.="'".$url."'";
						$script_carts_photo.="'".$preview."'";
						$script_carts_description.="'".addslashes($dr->row["title"])."'";
						$script_carts_url2.=$url;
						$script_carts_photo2.=$preview;
						$script_carts_description2.=addslashes($dr->row["title"]);
					}
				}
			}
		
			$quantity+=$rs->row["quantity"];
			
			$script_carts_qty.=$rs->row["quantity"];
			$script_carts_qty2.=$rs->row["quantity"];
			
			$rs->movenext();
		}
	
		$box_shopping_cart.="</table><div class=smalltext style='margin-top:5'><b>".word_lang("total").":</b> ".currency(1).float_opt($total,2,true)." ".currency(2)."</div><div style='margin-top:5'><a href='".site_root."/members/shopping_cart.php' class='o'><b>".word_lang("view shopping cart")."</b></a></div>";

		$box_shopping_cart_lite="<a href='".site_root."/members/shopping_cart.php'>".word_lang("shopping cart")."</a> ".$quantity." (".currency(1).float_opt($total,2,true)." ".currency(2).")" ;
	}

	$script_carts="<script>
	cart_mass=new Array();
	cart_mass = [".$script_carts."];
	cart_title=new Array();
	cart_title=[".$script_carts_title."];
	cart_price=new Array();
	cart_price=[".$script_carts_price."];
	cart_qty=new Array();
	cart_qty=[".$script_carts_qty."];
	cart_url=new Array();
	cart_url=[".$script_carts_url."];
	cart_photo=new Array();
	cart_photo=[".$script_carts_photo."];
	cart_description=new Array();
	cart_description=[".$script_carts_description."];
	cart_remove=new Array();
	cart_remove=[".$script_carts_remove."];
	cart_content_id=new Array();
	cart_content_id=[".$script_carts_content_id."];
	</script><input type='hidden' id='list_cart_mass' value=\"".$script_carts2."\"><input type='hidden' id='list_cart_title' value=\"".$script_carts_title2."\"><input type='hidden' id='list_cart_price' value=\"".$script_carts_price2."\"><input type='hidden' id='list_cart_qty' value=\"".$script_carts_qty2."\"><input type='hidden' id='list_cart_url' value=\"".$script_carts_url2."\"><input type='hidden' id='list_cart_photo' value=\"".$script_carts_photo2."\"><input type='hidden' id='list_cart_description' value=\"".$script_carts_description2."\"><input type='hidden' id='list_cart_remove' value=\"".$script_carts_remove2."\"><input type='hidden' id='list_cart_content_id' value=\"".$script_carts_content_id2."\">";
	
	$box_shopping_cart.=$script_carts;
	$box_shopping_cart_lite.=$script_carts;
	
	$_SESSION["box_shopping_cart"]=$box_shopping_cart;
	$_SESSION["box_shopping_cart_lite"]=$box_shopping_cart_lite;
}
else
{
	$box_shopping_cart=$_SESSION["box_shopping_cart"];
	$box_shopping_cart_lite=$_SESSION["box_shopping_cart_lite"];
}
?>