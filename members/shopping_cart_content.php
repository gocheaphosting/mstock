<? include("../admin/function/show.php");?>
<?
$cart_id=shopping_cart_id();
$total=0;
$tax_total=0;
$quantity=0;

$photo_formats=array();
if($global_settings["allow_photo"])
{
	$sql="select id,photo_type from photos_formats where enabled=1 order by id";
	$dr->open($sql);
	while(!$dr->eof)
	{
		$photo_formats[$dr->row["id"]]=$dr->row["photo_type"];
		$dr->movenext();
	}
}

$sql="select id,item_id,prints_id,publication_id,quantity,option1_id,option1_value,option2_id,option2_value,option3_id,option3_value,option4_id,option4_value,option5_id,option5_value,option6_id,option6_value,option7_id,option7_value,option8_id,option8_value,option9_id,option9_value,option10_id,option10_value,rights_managed,printslab,stock,stock_type,stock_id,stock_url,stock_preview,stock_site_url from carts_content where id_parent=".$cart_id;
$dq->open($sql);
if(!$dq->eof)
{
	?>
		<table border="0" cellpadding="8" cellspacing="0"  class='table_cart2' style="width:100%">
		<tr>
		<th class='hidden-phone hidden-tablet'><b><?=word_lang("item")?></b></td>
		<th><b><?=word_lang("title")?></b></th>
		<th class='hidden-phone'></th>
		<th class='hidden-phone hidden-tablet'><b><?=word_lang("price")?></b></th>
		<th><b><?=word_lang("qty")?></b></th>
		<th></th>
		</tr>
	<?
	while(!$dq->eof)
	{
		if($dq->row["item_id"]>0)
		{
			//Download items
			$sql="select id,name,price,id_parent,url,shipped from items where id=".$dq->row["item_id"];
			$dr->open($sql);
			if(!$dr->eof)
			{
				$folder="";
				$server1="";				
				$fl="photos";
				$url=item_url($dr->row["id_parent"]);

				$sql="select id_parent,title,server1,url_jpg,url_png,url_gif,url_raw,url_tiff,url_eps from photos where id_parent=".(int)$dr->row["id_parent"];
				$rs->open($sql);
				if(!$rs->eof)
				{					
					$translate_results=translate_publication($rs->row["id_parent"],$rs->row["title"],"","");
					$title=$translate_results["title"];
					$folder=$rs->row["id_parent"];
					
					$photo_files=array();
					foreach ($photo_formats as $key => $value) 
					{
						$photo_files[$value]=$rs->row["url_".$value];
					}

					$sql="select width,height from filestorage_files where id_parent=".$rs->row["id_parent"]." and item_id<>0";
					$ds->open($sql);
					if(!$ds->eof)
					{
						$photo_width=$ds->row["width"];
						$photo_height=$ds->row["height"];
					}
					else
					{
						if(file_exists($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$folder."/".$dr->row["url"]))
						{
							$size = getimagesize($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".$folder."/".$dr->row["url"]);
							$photo_width=$size[0];
							$photo_height=$size[1];
						}
					}
					
					$rw=$photo_width;
					$rh=$photo_height;
		
					if($photo_width!=0 and $photo_height!=0)
					{
						$sql="select * from sizes where title='".$dr->row["name"]."'";
						$ds->open($sql);
						if(!$ds->eof)
						{
							if($ds->row["size"]!=0)
							{
								if($rw>$rh)
								{
									$rw=$ds->row["size"];
									if($rw!=0)
									{
										$rh=round($photo_height*$rw/$photo_width);
									}
								}
								else
								{
									$rh=$ds->row["size"];
									if($rh!=0)
									{
										$rw=round($photo_width*$rh/$photo_height);
									}
								}
							}
						}
					}
					$fl="photos";
					$server1=$rs->row["server1"];
					$preview=show_preview($rs->row["id_parent"],"photo",1,1,$rs->row["server1"],$rs->row["id_parent"]);
				}

				$sql="select id_parent,title,server1 from videos where id_parent=".(int)$dr->row["id_parent"];
				$rs->open($sql);
				if(!$rs->eof)
				{
					$translate_results=translate_publication($rs->row["id_parent"],$rs->row["title"],"","");
					$title=$translate_results["title"];
					$folder=$rs->row["id_parent"];
					$fl="videos";
					$server1=$rs->row["server1"];
					$preview=show_preview($rs->row["id_parent"],"video",1,1,$rs->row["server1"],$rs->row["id_parent"]);
				}

				$sql="select id_parent,title,server1 from audio where id_parent=".(int)$dr->row["id_parent"];
				$rs->open($sql);
				if(!$rs->eof)
				{
					$translate_results=translate_publication($rs->row["id_parent"],$rs->row["title"],"","");
					$title=$translate_results["title"];
					$folder=$rs->row["id_parent"];
					$fl="audio";
					$server1=$rs->row["server1"];
					$preview=show_preview($rs->row["id_parent"],"audio",1,1,$rs->row["server1"],$rs->row["id_parent"]);
				}

				$sql="select id_parent,title,server1 from vector where id_parent=".(int)$dr->row["id_parent"];
				$rs->open($sql);
				if(!$rs->eof)
				{
					$translate_results=translate_publication($rs->row["id_parent"],$rs->row["title"],"","");
					$title=$translate_results["title"];
					$folder=$rs->row["id_parent"];
					$fl="vector";
					$server1=$rs->row["server1"];
					$preview=show_preview($rs->row["id_parent"],"vector",1,1,$rs->row["server1"],$rs->row["id_parent"]);
				}
				?>
					<tr valign="top"  class='tr_cart'>
					<td class='hidden-phone hidden-tablet'><div class="white_t"><div class="white_b"><div class="white_l"><div class="white_r"><div class="white_bl"><div class="white_br"><div class="white_tl"><div class="white_tr"><a href="<?=$url?>"><img src="<?=$preview?>" border="0"></a></div></div></div></div></div></div></div></div></td>
					<td><a href="<?=$url?>"><?=$title?></a><div class="gr">ID: <?=$dr->row["id_parent"]?></div></td>
					<td class='hidden-phone'>
					<?if($dq->row["rights_managed"]!=""){?>
						<div class="ttl"><b><?=word_lang("rights managed")?>:</b></div>
						<ul>
						<?
							$rights_managed_price=0;
							$rights_mass=explode("|",$dq->row["rights_managed"]);
							for($i=0;$i<count($rights_mass);$i++)
							{
								if($i==0)
								{
									$rights_managed_price=$rights_mass[$i];
								}
								else
								{
									$rights_mass2=explode("-",$rights_mass[$i]);
									if(isset($rights_mass2[0]) and isset($rights_mass2[1]))
									{
										$sql="select title from rights_managed_structure where id=".(int)$rights_mass2[0]." and  types=1";
										$ds->open($sql);
										if(!$ds->eof)
										{
											?><li><b><?=$ds->row["title"]?>:</b> <?
										}
										
										$sql="select title from rights_managed_structure where id=".(int)$rights_mass2[1]." and  types=2";
										$ds->open($sql);
										if(!$ds->eof)
										{
											?><?=$ds->row["title"]?></li><?
										}
									}
								}
							}
							$total+=$rights_managed_price;
						?>
						</ul>
					<?}else{?>
						<div class="ttl"><?=word_lang("file")?>:</div><select style="width:320px" class='ibox form-control' onChange="cart_add(<?=$dq->row["id"]?>,this.value);">
						<?
						$sql="select id_parent,name from licenses order by priority";
						$ds->open($sql);
						while(!$ds->eof)
						{
							if($fl=="photos")
							{
								$sql="select id_parent,title,size,jpg,png,gif,raw,tiff,eps from sizes where license=".$ds->row["id_parent"]." order by priority";
							}
							if($fl=="videos")
							{
								$sql="select id_parent,title from video_types where license=".$ds->row["id_parent"]." order by priority";
							}
							if($fl=="audio")
							{
								$sql="select id_parent,title from audio_types where license=".$ds->row["id_parent"]." order by priority";
							}
							if($fl=="vector")
							{
								$sql="select id_parent,title from vector_types where license=".$ds->row["id_parent"]." order by priority";
							}
							$dd->open($sql);
							while(!$dd->eof)
							{	
								if($fl=="photos")
								{
									$flag_format=false;
									foreach ($photo_formats as $key => $value) 
									{
										if($photo_files[$value]!="" and $dd->row[$value]==1)
										{
											$flag_format=true;
										}
									}		
									
									
									if((($photo_width>=$photo_height and $dd->row["size"]<=$photo_width) or ($photo_width<$photo_height and $dd->row["size"]<=$photo_height)) and $flag_format)
									{
										$flag_size=true;
									}
									else
									{
										$flag_size=false;
									}
								}
								else
								{
									$flag_size=true;
								}
															
								$sql="select id,name,url,price from items where price_id=".$dd->row["id_parent"]." and id_parent=".$dr->row["id_parent"]." and price<>0 order by priority";
								$dn->open($sql);
								if(!$dn->eof and $flag_size)
								{
									$chk="";
									if($dn->row["id"]==$dq->row["item_id"])
									{
										$chk="selected";
									}
									?>
										<option value='<?=$dn->row["id"]?>' <?=$chk?>><?=$ds->row["name"]?> - <?=$dn->row["name"]?></option>
									<?
								}
								$dd->movenext();
							}
							$ds->movenext();
						}
						?>
						</select>
					<?
						$total+=$dr->row["price"]*$dq->row["quantity"];
					}
					?>
					</td>
					<td class='hidden-phone hidden-tablet'><span class="price"><b><?=currency(1);?>
					<?if($dq->row["rights_managed"]!=""){?>
						<?=float_opt($rights_managed_price,2,true)?>
					<?}else{?>
						<?=float_opt($dr->row["price"],2,true)?>
					<?}?>
					&nbsp;<?=currency(2);?></b></span>
					
					<?
					if($global_settings["taxes_cart"] and  (!$global_settings["credits"] or $global_settings["credits_currency"]))
					{
						$taxes_info=array();
						if($dr->row["shipped"]!=1)
						{
							if($dq->row["rights_managed"]!="")
							{
								order_taxes_calculate($rights_managed_price,false,"order");
							}
							else
							{
								order_taxes_calculate($dr->row["price"],false,"order");							
							}
						}
						else
						{
							order_taxes_calculate($dr->row["price"],false,"prints");
						}
						
						if($taxes_info["total"]!=0)
						{
							echo("<div><small><b>".$taxes_info["text"].": ".currency(1,false).$taxes_info["total"]." ".currency(2,false)."</b></small></div>");
							$tax_total += $taxes_info["total"];
						}
					}
					?>
					</td>
					<td><?if($dr->row["shipped"]!=1){?>1<?}else{?>

					<select class='ibox form-control' onChange="cart_change(<?=$dq->row["id"]?>,this.value);" style="width:80px">
					<?
					for($j=1;$j<100;$j++)
					{
						if($j==$dq->row["quantity"]){$sel="selected";}
						else{$sel="";}
						?>
							<option value="<?=$j?>" <?=$sel?>><?=$j?></option>
						<?
					}
					?>
					</select>
					<?}?></td>
					<td><div class="link_delete"><a href="#" onClick="cart_delete(<?=$dq->row["id"]?>);"><?=word_lang("delete")?></a></div></td>
					</tr>
					<?
				}
			}
			
			if($dq->row["prints_id"]>0)
			{
				if((int)$dq->row["stock"] == 0)
				{
					if($dq->row["printslab"]<>1)
					{
						//Prints items
						$sql="select id_parent,title,price,itemid,printsid from prints_items where id_parent=".$dq->row["prints_id"];
						$dr->open($sql);
						if(!$dr->eof)
						{
							$folder="";
							$url=item_url($dr->row["itemid"]);
							$sql="select id_parent,title,server1 from photos where id_parent=".(int)$dr->row["itemid"];
							$rs->open($sql);
							if(!$rs->eof)
							{
								$translate_results=translate_publication($rs->row["id_parent"],$rs->row["title"],"","");
								$title=$translate_results["title"];
								$folder=$rs->row["id_parent"];
								$server1=$rs->row["server1"];
								$preview=show_preview($rs->row["id_parent"],"photo",1,1,$rs->row["server1"],$rs->row["id_parent"]);
							}
							?>
							<tr valign="top"   class='tr_cart'>
							<td><div class="white_t"><div class="white_b"><div class="white_l"><div class="white_r"><div class="white_bl"><div class="white_br"><div class="white_tl"><div class="white_tr"><a href="<?=$url?>"><img src="<?=$preview?>" border="0"></a></div></div></div></div></div></div></div></div></td>
							<td><a href="<?=$url?>"><?=$title?></a><div class="gr">ID: <?=$dr->row["itemid"]?></div></td>
							<td class='hidden-phone'><div class="ttl"><?=word_lang("prints and products")?>:</div>
							<select style="width:320px" class='ibox form-control' onChange="cart_add(<?=-1*$dq->row["id"]?>,this.value);">
							<?
							$printsid="";
							$sql="select id_parent from prints where photo=1";
							$ds->open($sql);
							while(!$ds->eof)
							{
								if($printsid!="")
								{
									$printsid.=" or ";
								}
								$printsid.="printsid=".$ds->row["id_parent"];
								$ds->movenext();
							}
				
							if($printsid!="")
							{
								$printsid=" and (".$printsid.")";
							}
													
							$sql="select id_parent,title,price from prints_items where itemid=".(int)$dr->row["itemid"].$printsid." order by priority";
							$ds->open($sql);
							while(!$ds->eof)
							{
								$chk="";
								if($dq->row["prints_id"]==$ds->row["id_parent"])
								{
									$chk="selected";
								}
								?>
									<option value="<?=-1*$ds->row["id_parent"]?>" <?=$chk?>><?=$ds->row["title"]?></option>
								<?
								$ds->movenext();
							}
							?>
							</select>
											
							<?
							$prints_content="";
							$sql="select option1,option2,option3,option4,option5,option6,option7,option8,option9,option10 from prints where photo=1 and id_parent=".$dr->row["printsid"];
							$dd->open($sql);
							if(!$dd->eof)
							{
								for($i=1;$i<11;$i++)
								{
									if($dd->row["option".$i]!=0)
									{
										$sql="select title,type,activ,required from products_options where activ=1 and id=".$dd->row["option".$i];
										$dn->open($sql);
										if(!$dn->eof)
										{
											$prints_content.="<div class='ttl' style='margin-top:15px'>".$dn->row["title"].":</div><div>";
				
											if($dn->row["type"]=="selectform")
											{
												$prints_content.="<select onChange=\"cart_change_option(".$dq->row["id"].",".$i.",".$dd->row["option".$i].",this.value)\" class='ibox form-control' style='width:150px'>";
											}
				
											$sql="select id,title,price,adjust from products_options_items where id_parent=".$dd->row["option".$i]." order by id";
											$ds->open($sql);
											while(!$ds->eof)
											{
												$sel="";
												$sel2="";
					
												if($dq->row["option".$i."_value"]==$ds->row["title"])
												{
													$sel="selected";
													$sel2="checked";
												}
					
												if($dn->row["type"]=="selectform")
												{
													$prints_content.="<option value='".$ds->row["title"]."' ".$sel.">".$ds->row["title"]."</option>";
												}
												if($dn->row["type"]=="radio")
												{
													$prints_content.="<input onClick=\"cart_change_option(".$dq->row["id"].",".$i.",".$dd->row["option".$i].",'".$ds->row["title"]."')\" name='option".$dq->row["id"]."_".$dd->row["option".$i]."' type='radio' value='".$ds->row["title"]."' ".$sel2.">&nbsp;".$ds->row["title"]."&nbsp;&nbsp;";
												}
					
												$ds->movenext();
											}
				
											if($dn->row["type"]=="selectform")
											{
												$prints_content.="</select>";
											}
				
											$prints_content.="</div>";
										}
									}
								}
							}
							echo($prints_content);
						
							$price=define_prints_price($dr->row["price"],$dq->row["option1_id"],$dq->row["option1_value"],$dq->row["option2_id"],$dq->row["option2_value"],$dq->row["option3_id"],$dq->row["option3_value"],$dq->row["option4_id"],$dq->row["option4_value"],$dq->row["option5_id"],$dq->row["option5_value"],$dq->row["option6_id"],$dq->row["option6_value"],$dq->row["option7_id"],$dq->row["option7_value"],$dq->row["option8_id"],$dq->row["option8_value"],$dq->row["option9_id"],$dq->row["option9_value"],$dq->row["option10_id"],$dq->row["option10_value"]);
							?>
							</td>
							<td class='hidden-phone hidden-tablet'><span class="price"><b><?=currency(1);?><?=float_opt($price,2,true)?> <?=currency(2);?></b></span>
							
							<?
							if($global_settings["taxes_cart"] and  (!$global_settings["credits"] or $global_settings["credits_currency"]))
							{
								$taxes_info=array();
								order_taxes_calculate($price,false,"prints");
							
								if($taxes_info["total"]!=0)
								{
									echo("<div><small><b>".$taxes_info["text"].": ".currency(1,false).float_opt($taxes_info["total"]*$dq->row["quantity"],2)." ".currency(2,false)."</b></small></div>");
									$tax_total += $taxes_info["total"]*$dq->row["quantity"];
								}
							}
							?>
							
							
							</td>
							<td><select class='ibox form-control' onChange="cart_change(<?=$dq->row["id"]?>,this.value);" style="width:80px">
							<?
							for($j=1;$j<10+1;$j++)
							{
								if($j==$dq->row["quantity"]){$sel="selected";}
								else{$sel="";}
								?>
									<option value="<?=$j?>" <?=$sel?>><?=$j?></option>
								<?
							}
							?>
							</select></td>
							<td><div class="link_delete"><a href="#" onClick="cart_delete(<?=$dq->row["id"]?>);"><?=word_lang("delete")?></a></div></td>
							</tr>
							<?
							$total+=$price*$dq->row["quantity"];
						}
					}
					else
					{
						//Printslab items
						$sql="select id_parent,title,price from prints where printslab=1 and id_parent=".$dq->row["prints_id"];
						$dr->open($sql);
						if(!$dr->eof)
						{
							$sql="select id,title,photo,id_parent from galleries_photos where id=".(int)$dq->row["publication_id"];
							$rs->open($sql);
							if(!$rs->eof)
							{
								$title=$rs->row["title"];
								$preview=site_root."/content/galleries/".$rs->row["id_parent"]."/thumb".$rs->row["id"].".jpg";
								$url="printslab_content.php?id=".$rs->row["id_parent"];
							}
							?>
							<tr valign="top"   class='tr_cart'>
							<td><div class="white_t"><div class="white_b"><div class="white_l"><div class="white_r"><div class="white_bl"><div class="white_br"><div class="white_tl"><div class="white_tr"><a href="<?=$url?>"><img src="<?=$preview?>" border="0"></a></div></div></div></div></div></div></div></div></td>
							<td><a href="<?=$url?>"><?=$title?></a><div class="gr"><?=word_lang("prints lab")?> ID: <?=$dq->row["publication_id"]?></div></td>
							<td class='hidden-phone'><div class="ttl"><?=word_lang("prints and products")?>:</div>
							<select style="width:320px" class='ibox form-control' onChange="cart_add(<?=-1*$dq->row["id"]?>,this.value);">
							<?
							$sql="select id_parent,title,price from prints where printslab=1 order by priority";
							$ds->open($sql);
							while(!$ds->eof)
							{
								$chk="";
								if($dq->row["prints_id"]==$ds->row["id_parent"])
								{
									$chk="selected";
								}
								?>
									<option value="<?=-1*$ds->row["id_parent"]?>" <?=$chk?>><?=$ds->row["title"]?></option>
								<?
								$ds->movenext();
							}
							?>
							</select>
						
						
							<?
							$prints_content="";
							$sql="select option1,option2,option3,option4,option5,option6,option7,option8,option9,option10 from prints where printslab=1 and  id_parent=".$dq->row["prints_id"];
							$dd->open($sql);
							if(!$dd->eof)
							{
								for($i=1;$i<11;$i++)
								{
									if($dd->row["option".$i]!=0)
									{
										$sql="select title,type,activ,required from products_options where activ=1 and id=".$dd->row["option".$i];
										$dn->open($sql);
										if(!$dn->eof)
										{
											$prints_content.="<div class='ttl' style='margin-top:15px'>".$dn->row["title"].":</div><div>";
				
											if($dn->row["type"]=="selectform")
											{
												$prints_content.="<select onChange=\"cart_change_option(".$dq->row["id"].",".$i.",".$dd->row["option".$i].",this.value)\" class='ibox form-control' style='width:150px'>";
											}
				
											$sql="select id,title,price,adjust from products_options_items where id_parent=".$dd->row["option".$i]." order by id";
											$ds->open($sql);
											while(!$ds->eof)
											{
												$sel="";
												$sel2="";
					
												if($dq->row["option".$i."_value"]==$ds->row["title"])
												{
													$sel="selected";
													$sel2="checked";
												}
					
												if($dn->row["type"]=="selectform")
												{
													$prints_content.="<option value='".$ds->row["title"]."' ".$sel.">".$ds->row["title"]."</option>";
												}
												if($dn->row["type"]=="radio")
												{
													$prints_content.="<input onClick=\"cart_change_option(".$dq->row["id"].",".$i.",".$dd->row["option".$i].",'".$ds->row["title"]."')\" name='option".$dq->row["id"]."_".$dd->row["option".$i]."' type='radio' value='".$ds->row["title"]."' ".$sel2.">&nbsp;".$ds->row["title"]."&nbsp;&nbsp;";
												}
					
												$ds->movenext();
											}
				
											if($dn->row["type"]=="selectform")
											{
												$prints_content.="</select>";
											}
				
											$prints_content.="</div>";
										}
									}
								}
							}
							echo($prints_content);
						
							$price=define_prints_price($dr->row["price"],$dq->row["option1_id"],$dq->row["option1_value"],$dq->row["option2_id"],$dq->row["option2_value"],$dq->row["option3_id"],$dq->row["option3_value"],$dq->row["option4_id"],$dq->row["option4_value"],$dq->row["option5_id"],$dq->row["option5_value"],$dq->row["option6_id"],$dq->row["option6_value"],$dq->row["option7_id"],$dq->row["option7_value"],$dq->row["option8_id"],$dq->row["option8_value"],$dq->row["option9_id"],$dq->row["option9_value"],$dq->row["option10_id"],$dq->row["option10_value"]);
							?>
							</td>
							<td class='hidden-phone hidden-tablet'><span class="price"><b><?=currency(1);?><?=float_opt($price,2,true)?> <?=currency(2);?></b></span>
							
							<?
							if($global_settings["taxes_cart"] and  (!$global_settings["credits"] or $global_settings["credits_currency"]))
							{
								$taxes_info=array();
								order_taxes_calculate($price,false,"prints");
							
								if($taxes_info["total"]!=0)
								{
									echo("<div><small><b>".$taxes_info["text"].": ".currency(1,false).float_opt($taxes_info["total"]*$dq->row["quantity"],2)." ".currency(2,false)."</b></small></div>");
									$tax_total += $taxes_info["total"]*$dq->row["quantity"];
								}
							}
							?>						
							
							</td>
							<td><select class='ibox form-control' onChange="cart_change(<?=$dq->row["id"]?>,this.value);" style="width:80px">
							<?
							for($j=1;$j<10+1;$j++)
							{
								if($j==$dq->row["quantity"]){$sel="selected";}
								else{$sel="";}
								?>
									<option value="<?=$j?>" <?=$sel?>><?=$j?></option>
								<?
							}
							?>
							</select></td>
							<td><div class="link_delete"><a href="#" onClick="cart_delete(<?=$dq->row["id"]?>);"><?=word_lang("delete")?></a></div></td>
							</tr>
							<?
							$total+=$price*$dq->row["quantity"];							
						}
					}
				}
				else
				{
					//Prints stock
					$sql="select id_parent,title,price from prints where id_parent=".$dq->row["prints_id"];
					$dr->open($sql);
					if(!$dr->eof)
					{
						$title=@$mstocks[$dq->row["stock_type"]]." #".$dq->row["stock_id"];
						$preview=$dq->row["stock_preview"];
						$url=$dq->row["stock_site_url"];
						?>
						<tr valign="top"   class='tr_cart'>
						<td><div class="white_t"><div class="white_b"><div class="white_l"><div class="white_r"><div class="white_bl"><div class="white_br"><div class="white_tl"><div class="white_tr"><a href="<?=$url?>"><img src="<?=$preview?>" border="0" style="width:<?=$global_settings["thumb_width"]?>px"></a></div></div></div></div></div></div></div></div></td>
						<td><a href="<?=$url?>"><?=$title?></a></td>
						<td class='hidden-phone'><div class="ttl"><?=word_lang("prints and products")?>:</div>
						<select style="width:320px" class='ibox form-control' onChange="cart_add(<?=-1*$dq->row["id"]?>,this.value);">
						<?
						$sql="select id_parent,title,price from prints order by priority";
						$ds->open($sql);
						while(!$ds->eof)
						{
							$chk="";
							if($dq->row["prints_id"]==$ds->row["id_parent"])
							{
								$chk="selected";
							}
							?>
								<option value="<?=-1*$ds->row["id_parent"]?>" <?=$chk?>><?=$ds->row["title"]?></option>
							<?
							$ds->movenext();
						}
						?>
						</select>
					
					
						<?
						$prints_content="";
						$sql="select option1,option2,option3,option4,option5,option6,option7,option8,option9,option10 from prints where id_parent=".$dq->row["prints_id"];
						$dd->open($sql);
						if(!$dd->eof)
						{
							for($i=1;$i<11;$i++)
							{
								if($dd->row["option".$i]!=0)
								{
									$sql="select title,type,activ,required from products_options where activ=1 and id=".$dd->row["option".$i];
									$dn->open($sql);
									if(!$dn->eof)
									{
										$prints_content.="<div class='ttl' style='margin-top:15px'>".$dn->row["title"].":</div><div>";
			
										if($dn->row["type"]=="selectform")
										{
											$prints_content.="<select onChange=\"cart_change_option(".$dq->row["id"].",".$i.",".$dd->row["option".$i].",this.value)\" class='ibox form-control' style='width:150px'>";
										}
			
										$sql="select id,title,price,adjust from products_options_items where id_parent=".$dd->row["option".$i]." order by id";
										$ds->open($sql);
										while(!$ds->eof)
										{
											$sel="";
											$sel2="";
				
											if($dq->row["option".$i."_value"]==$ds->row["title"])
											{
												$sel="selected";
												$sel2="checked";
											}
				
											if($dn->row["type"]=="selectform")
											{
												$prints_content.="<option value='".$ds->row["title"]."' ".$sel.">".$ds->row["title"]."</option>";
											}
											if($dn->row["type"]=="radio")
											{
												$prints_content.="<input onClick=\"cart_change_option(".$dq->row["id"].",".$i.",".$dd->row["option".$i].",'".$ds->row["title"]."')\" name='option".$dq->row["id"]."_".$dd->row["option".$i]."' type='radio' value='".$ds->row["title"]."' ".$sel2.">&nbsp;".$ds->row["title"]."&nbsp;&nbsp;";
											}
				
											$ds->movenext();
										}
			
										if($dn->row["type"]=="selectform")
										{
											$prints_content.="</select>";
										}
			
										$prints_content.="</div>";
									}
								}
							}
						}
						echo($prints_content);
					
						$price=define_prints_price($dr->row["price"],$dq->row["option1_id"],$dq->row["option1_value"],$dq->row["option2_id"],$dq->row["option2_value"],$dq->row["option3_id"],$dq->row["option3_value"],$dq->row["option4_id"],$dq->row["option4_value"],$dq->row["option5_id"],$dq->row["option5_value"],$dq->row["option6_id"],$dq->row["option6_value"],$dq->row["option7_id"],$dq->row["option7_value"],$dq->row["option8_id"],$dq->row["option8_value"],$dq->row["option9_id"],$dq->row["option9_value"],$dq->row["option10_id"],$dq->row["option10_value"]);
						?>
						</td>
						<td class='hidden-phone hidden-tablet'><span class="price"><b><?=currency(1);?><?=float_opt($price,2,true)?> <?=currency(2);?></b></span>
						
						<?
						if($global_settings["taxes_cart"] and  (!$global_settings["credits"] or $global_settings["credits_currency"]))
						{
							$taxes_info=array();
							order_taxes_calculate($price,false,"prints");
						
							if($taxes_info["total"]!=0)
							{
								echo("<div><small><b>".$taxes_info["text"].": ".currency(1,false).float_opt($taxes_info["total"]*$dq->row["quantity"],2)." ".currency(2,false)."</b></small></div>");
								$tax_total += $taxes_info["total"]*$dq->row["quantity"];
							}
						}
						?>						
						
						</td>
						<td><select class='ibox form-control' onChange="cart_change(<?=$dq->row["id"]?>,this.value);" style="width:80px">
						<?
						for($j=1;$j<10+1;$j++)
						{
							if($j==$dq->row["quantity"]){$sel="selected";}
							else{$sel="";}
							?>
								<option value="<?=$j?>" <?=$sel?>><?=$j?></option>
							<?
						}
						?>
						</select></td>
						<td><div class="link_delete"><a href="#" onClick="cart_delete(<?=$dq->row["id"]?>);"><?=word_lang("delete")?></a></div></td>
						</tr>
						<?
						$total+=$price*$dq->row["quantity"];							
					}				
				}
			}
		$quantity+=$dq->row["quantity"];
		$dq->movenext();
	}
	?>
	<tr class="total">
	<td colspan="6"><b><?=word_lang("total")?>:</b> <span class="price"><b><?=currency(1);?><?=float_opt($total,2,true)?> <?=currency(2);?></b></span>
	
	<?
	if($global_settings["taxes_cart"] and (!$global_settings["credits"] or $global_settings["credits_currency"]))
	{					
		if($tax_total != 0)
		{
			echo("&nbsp;&nbsp;&nbsp;&nbsp;<small><b>".word_lang("taxes").": ".currency(1,false).float_opt($tax_total,2)." ".currency(2,false)."</b></small>");
		}
	}
	?>
	</td>
	</tr>
	</table><input class='isubmit btn' type="button" value="<?=word_lang("checkout")?>" onClick="location.href='<?=site_root?>/members/checkout.php'" style="margin-top:5px;margin-left:10px">&nbsp;&nbsp;&nbsp;<input class='isubmit_orange btn' type="button" value="<?=word_lang("clear cart")?>" onClick="cart_clear(<?=$cart_id?>)" style="margin-top:5px;margin-left:10px">
	<?
}
else
{
	echo(word_lang("empty shopping cart"));
}

?>