<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_orders");

?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>



<div class="back"><a href="index.php" class="btn btn-mini"><i class="icon-arrow-left"></i> <?=word_lang("back")?></a></div>


<a class="btn btn-success toright" href="print_version.php?id=<?=$_GET["id"]?>"><i class="icon-print icon-white fa fa-print"></i>&nbsp; <?=word_lang("print version")?></a>


<h1><?=word_lang("order")?> #<?=$_GET["id"]?></h1>




<script type="text/javascript" language="JavaScript" src="../../members/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">
function doLoad(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('status'+value).innerHTML =req.responseText;

        }
    }
    req.open(null, 'status.php', true);
    req.send( {'id': value} );
}



function doLoad4(value) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('shipping'+value).innerHTML =req.responseText;

        }
    }
    req.open(null, 'shipping.php', true);
    req.send( {'id': value} );
}
</script>












<?
$sql="select * from orders where id=".(int)$_GET["id"]." order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
	$cl="success";
	if($rs->row["status"]!=1)
	{
		$cl="danger";
	}
	
	
	$cl2="info";
	if($rs->row["shipped"]!=1)
	{
		$cl2="warning";
	}
	
	
	$method="";
	if($global_settings["credits_currency"])
	{
		if($rs->row["credits"]==1)
		{
			$method="credits";
		}
		else
		{
			$method="currency";
		}
	}
	?>

	<div class="row-fluid" style="margin:20px 0px 20px 0px">
		<div class="span4 col-md-4 nopadding" style="padding:0px 5px 0px 0px">

		<div class="table_t" style="display:block"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr" style="height:<?if($global_settings["credits"] and !$global_settings["credits_currency"]){echo(320);}else{echo(390);}?>px;">

		<table border="0" cellpadding="0" cellspacing="0" class="profile_table_home" style="width:90%">
		
		<tr>
			<th colspan="2"><?=word_lang("order details")?></th>
		</tr>
		<tr>
			<td><b><?=word_lang("date")?>:</b></td>
			<td><div class="link_date"><?=date(date_format,$rs->row["data"])?></div></td>
		</tr>
		<tr>
			<td><b><?=word_lang("status")?>:</b></td>
			<td><div id="status<?=$rs->row["id"]?>" name="status<?=$rs->row["id"]?>"><a href="javascript:doLoad(<?=$rs->row["id"]?>);"><span class='label label-<?=$cl?>'><?if($rs->row["status"]==1){echo(word_lang("approved"));}else{echo(word_lang("pending"));}?></span></a></div>
			</td>
		</tr>
		<tr>
			<td><b><?=word_lang("shipping")?>:</b></td>
			<td>
				<?
					if($rs->row["shipping"]*1!=0)
					{
						?>
						<div id="shipping<?=$rs->row["id"]?>" name="status<?=$rs->row["id"]?>"><a href="javascript:doLoad4(<?=$rs->row["id"]?>);"><span class='label label-<?=$cl2?>'><?if($rs->row["shipped"]==1){echo(word_lang("shipped"));}else{echo(word_lang("not shipped"));}?></span></a></div>
						<?
					}
					else
					{
						echo("<span class='label label-default'>".word_lang("digital")."</span>");
					}
				?>
			</td>
		</tr>
		<tr>
			<td><b><?=word_lang("subtotal")?>:</b></td>
			<td><?=currency(1,true,$method);?><?=float_opt($rs->row["subtotal"],2)?> <?=currency(2,true,$method);?></td>
		</tr>
	<?if(!$global_settings["credits"] or $global_settings["credits_currency"]){?>
		<tr>
			<td><b><?=word_lang("discount")?>:</b></td>
			<td> <?=currency(1,true,$method);?><?=float_opt($rs->row["discount"],2)?> <?=currency(2,true,$method);?></td>
		</tr>
	<?}?>
		<tr>
			<td><b><?=word_lang("shipping")?>:</b></td>
			<td><?=currency(1,true,$method);?><?=float_opt($rs->row["shipping"],2)?> <?=currency(2,true,$method);?></td>
		</tr>
	<?
	if(!$global_settings["credits"] or $global_settings["credits_currency"])
	{
		if($rs->row["credits"]!=1)
		{
		?>
			<tr>
				<td><b><?=word_lang("taxes")?>:</b></td>
				<td><?=currency(1,true,$method);?><?=float_opt($rs->row["tax"],2)?> <?=currency(2,true,$method);?></td>
			</tr>
		<?
		}
		else
		{
		?>
			<tr>
				<td><b><?=word_lang("taxes")?>:</b></td>
				<td>&mdash;</td>
			</tr>
		<?
		}
	}
	?>
		<tr>
			<td><b><?=word_lang("total")?>:</b></td>
			<td><span class="price"><b><?=currency(1,true,$method);?><?=float_opt($rs->row["total"],2)?> <?=currency(2,true,$method);?></b></span></td>
		</tr>
	</table>
	</div></div></div></div></div></div></div></div>

	</div>
	<div class="span4 col-md-4 nopadding" style="padding:0px 5px 0px 5px">

	<div class="table_t" style="display:block"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr" style="height:<?if($global_settings["credits"] and !$global_settings["credits_currency"]){echo(320);}else{echo(390);}?>px">

	<table border="0" cellpadding="0" cellspacing="0" class="profile_table_home" style="width:90%">
		
		<tr>
			<th colspan="2"><?=word_lang("billing address")?></th>
		</tr>
		<tr>
			<td><b><?=word_lang("name")?>:</b></td>
			<td><?=$rs->row["billing_firstname"]." ".$rs->row["billing_lastname"]?></td>
		</tr>
		<tr valign="top">
			<td><b><?=word_lang("address")?>:</b></td>
			<td><?=str_replace("\n","<br>",$rs->row["billing_address"])?></td>
		</tr>
		<tr>
			<td><b><?=word_lang("city")?>:</b></td>
			<td><?=$rs->row["billing_city"]?></td>
		</tr>
		<tr>
			<td><b><?=word_lang("state")?>:</b></td>
			<td><?=$rs->row["billing_state"]?></td>
		</tr>
		<tr>
			<td><b><?=word_lang("zipcode")?>:</b></td>
			<td><?=$rs->row["billing_zip"]?></td>
		</tr>
		<tr>
			<td><b><?=word_lang("country")?>:</b></td>
			<td><?=$rs->row["billing_country"]?></td>
		</tr>
		
		
		

	</table>
	</div></div></div></div></div></div></div></div>


	</div>
	<div class="span4 col-md-4 nopadding" style="padding:0px 0px 0px 5px">

	<div class="table_t" style="display:block"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr" style="height:<?if($global_settings["credits"] and !$global_settings["credits_currency"]){echo(320);}else{echo(390);}?>px">


	<table border="0" cellpadding="0" cellspacing="0" class="profile_table_home" style="width:90%">
		
		<tr>
			<th colspan="2"><?=word_lang("shipping address")?></th>
		</tr>
		<tr>
			<td><b><?=word_lang("name")?>:</b></td>
			<td><?=$rs->row["shipping_firstname"]." ".$rs->row["shipping_lastname"]?></td>
		</tr>
		<tr valign="top">
			<td><b><?=word_lang("address")?>:</b></td>
			<td><?=str_replace("\n","<br>",$rs->row["shipping_address"])?></td>
		</tr>
		<tr>
			<td><b><?=word_lang("city")?>:</b></td>
			<td><?=$rs->row["shipping_city"]?></td>
		</tr>
		<tr>
			<td><b><?=word_lang("state")?>:</b></td>
			<td><?=$rs->row["shipping_state"]?></td>
		</tr>
		<tr>
			<td><b><?=word_lang("zipcode")?>:</b></td>
			<td><?=$rs->row["shipping_zip"]?></td>
		</tr>
		<tr>
			<td><b><?=word_lang("country")?>:</b></td>
			<td><?=$rs->row["shipping_country"]?></td>
		</tr>		
	</table>


	</div></div></div></div></div></div></div></div>


	</div>
	</div>

<div style="clear:both"></div>
	<br>
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="5" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th>&nbsp;</th>
	<th class="hidden-phone hidden-tablet"><b><?=word_lang("item")?></b></th>
	<th class="hidden-phone hidden-tablet"><b><?=word_lang("price")?></b></th>
	<th class="hidden-phone hidden-tablet"><b><?=word_lang("qty")?></b></th>
	<th><b><?=word_lang("download links")?></b></th>
	</tr>
	<?
	$tr=1;
	$sql="select * from orders_content where id_parent=".(int)$_GET["id"]." order by id";
	$dn->open($sql);
	while(!$dn->eof)
	{
		if($dn->row["prints"]!=1)
		{
			$sql="select id,name,price,id_parent,url,shipped from items where id=".$dn->row["item"];
			$dr->open($sql);
			if(!$dr->eof)
			{
				?>
				<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
				<td class='preview_img'>
				<?
				$folder="";
				$fl="photos";
				$url=item_url($dr->row["id_parent"]);

				$sql="select id_parent,title,server1 from photos where id_parent=".(int)$dr->row["id_parent"];
				$ds->open($sql);
				if(!$ds->eof)
				{
					$server1=$ds->row["server1"];
					$folder=$ds->row["id_parent"];
					
					$sql="select width,height from filestorage_files where id_parent=".$ds->row["id_parent"]." and item_id<>0";
					$dd->open($sql);
					if(!$dd->eof)
					{
						$photo_width=$dd->row["width"];
						$photo_height=$dd->row["height"];
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
						$dd->open($sql);
						if(!$dd->eof)
						{
							if($dd->row["size"]!=0)
							{
								if($rw>$rh)
								{
									$rw=$dd->row["size"];
									if($rw!=0)
									{
										$rh=round($photo_height*$rw/$photo_width);
									}
								}
								else
								{
									$rh=$dd->row["size"];
									if($rh!=0)
									{
										$rw=round($photo_width*$rh/$photo_height);
									}
								}
							}
						}
					}
					$fl="photos";
					$preview=show_preview($dr->row["id_parent"],"photo",1,1,$ds->row["server1"],$ds->row["id_parent"]);
				}

				$sql="select id_parent,title,server1 from videos where id_parent=".(int)$dr->row["id_parent"];
				$ds->open($sql);
				if(!$ds->eof)
				{
					$server1=$ds->row["server1"];
					$folder=$ds->row["id_parent"];
					$fl="videos";
					$preview=show_preview($dr->row["id_parent"],"video",1,1,$ds->row["server1"],$ds->row["id_parent"]);
				}

				$sql="select id_parent,title,server1 from audio where id_parent=".(int)$dr->row["id_parent"];
				$ds->open($sql);
				if(!$ds->eof)
				{
					$server1=$ds->row["server1"];
					$folder=$ds->row["id_parent"];
					$fl="audio";
					$preview=show_preview($dr->row["id_parent"],"audio",1,1,$ds->row["server1"],$ds->row["id_parent"]);
				}

				$sql="select id_parent,title,server1 from vector where id_parent=".(int)$dr->row["id_parent"];
				$ds->open($sql);
				if(!$ds->eof)
				{
					$server1=$ds->row["server1"];
					$folder=$ds->row["id_parent"];
					$fl="vector";
					$preview=show_preview($dr->row["id_parent"],"vector",1,1,$ds->row["server1"],$ds->row["id_parent"]);
				}
				?>

				<a href="<?=$url?>"><img src="<?=$preview?>" border="0"></a>
				</td>
				<td class="hidden-phone hidden-tablet">
				<a href="<?=$url?>">
				<b>#<?=$dr->row["id_parent"]?> &mdash; <?=$dr->row["name"]?></b></a><?if($fl=="photos"){?><br><?=$rw?>x<?=$rh?><?}?>

				<?
				$price=$dr->row["price"];
				?>

				<?if($dn->row["rights_managed"]!=""){?>
					<div style="margin-top:10px"><b><?=word_lang("rights managed")?>:</b></div>
						<?
							$rights_mass=explode("|",$dn->row["rights_managed"]);
							for($i=0;$i<count($rights_mass);$i++)
							{
								if($i==0)
								{
									$price=$rights_mass[$i];
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
											?><div class="gr" style="margin-bottom:3px"><b><?=$ds->row["title"]?>:</b> <?
										}
										
										$sql="select title from rights_managed_structure where id=".(int)$rights_mass2[1]." and  types=2";
										$ds->open($sql);
										if(!$ds->eof)
										{
											?><?=$ds->row["title"]?></div><?
										}
									}
								}
							}
						?>
					<?}?>

					</td>
					<td class="hidden-phone hidden-tablet"><span class="price"><?=currency(1,true,$method);?><?=float_opt($price,2)?> <?=currency(2,true,$method);?></span></td>
					<td class="hidden-phone hidden-tablet"><?=$dn->row["quantity"]?></td>
					<td>
					<?
					if($dr->row["shipped"]!=1)
					{
						if($rs->row["status"]==1)
						{
							$sql="select link,data,tlimit,ulimit from downloads where id_parent=".$dr->row["id"]." and order_id=".(int)$_GET["id"]." and data>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." and tlimit<ulimit+1";
							$ds->open($sql);
							if(!$ds->eof)
							{
								?>
								<b><?=word_lang("link")?>:</b> <a href="<?=surl?><?=site_root?>/members/download.php?f=<?=$ds->row["link"]?>" target="blank"><?=surl?><?=site_root?>/members/download.php?f=<?=$ds->row["link"]?></a><br>
								<b><?=word_lang("expiration date")?>:</b> <?=date(date_format,$ds->row["data"])?><br>
								<b><?=word_lang("times usage")?>:</b> <?=$ds->row["tlimit"]?>(<?=$ds->row["ulimit"]?>)<br>
								<?
							}
							else
							{
								echo(word_lang("expired")." - <a href='restore.php?id=".(int)$_GET["id"]."&link_id=".$dr->row["id"]."'>".word_lang("restore link")."</a>");
							}
						}
					}
					else
					{
						echo(word_lang("shipping"));
					}
					?>
					</td>
					</tr>
				<?
				}
			}
			else
			{
				if((int)$dn->row["stock"] == 0)
				{
					if($dn->row["printslab"]!=1)
					{
						//Prints items
						$sql="select id_parent,title,price,itemid from prints_items where id_parent=".$dn->row["item"];
						$dr->open($sql);
						if(!$dr->eof)
						{
							$folder="";
							$url=item_url($dr->row["itemid"]);
						
							$sql="select id_parent,title,server1 from photos where id_parent=".(int)$dr->row["itemid"];
							$ds->open($sql);
							if(!$ds->eof)
							{
								$server1=$ds->row["server1"];
								$title=$ds->row["title"];
								$folder=$ds->row["id_parent"];
								$preview=show_preview($ds->row["id_parent"],"photo",1,1,$ds->row["server1"],$ds->row["id_parent"]);
							}
							?>
							<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
							<td class='preview_img'><a href="<?=$url?>"><img src="<?=$preview?>" border="0"></a></td>
							<td><a href="<?=$url?>"><b>#<?=$dr->row["itemid"]?> &mdash; <?=word_lang("prints")?>: <?=$dr->row["title"]?></b></a>
							<?
							for($i=1;$i<11;$i++)
							{
								if($dn->row["option".$i."_id"]!=0 and $dn->row["option".$i."_value"]!="")
								{
									$sql="select title from products_options where id=".$dn->row["option".$i."_id"];
									$ds->open($sql);
									if(!$ds->eof)
									{
										?><div class="gr"><?=$ds->row["title"]?>: <?=$dn->row["option".$i."_value"]?>.</div> <?
									}
								}
							}
							
							$price_total=define_prints_price($dn->row["price"],$dn->row["option1_id"],$dn->row["option1_value"],$dn->row["option2_id"],$dn->row["option2_value"],$dn->row["option3_id"],$dn->row["option3_value"],$dn->row["option4_id"],$dn->row["option4_value"],$dn->row["option5_id"],$dn->row["option5_value"],$dn->row["option6_id"],$dn->row["option6_value"],$dn->row["option7_id"],$dn->row["option7_value"],$dn->row["option8_id"],$dn->row["option8_value"],$dn->row["option9_id"],$dn->row["option9_value"],$dn->row["option10_id"],$dn->row["option10_value"]);							
							?>
							</td>
							<td class="hidden-phone hidden-tablet"><span class="price"><?=currency(1,true,$method);?><?=float_opt($price_total,2)?> <?=currency(2,true,$method);?></span></td>
							<td class="hidden-phone hidden-tablet"><?=$dn->row["quantity"]?></td>
							<td><?=word_lang("shipping")?></td>
							</tr>
							<?
						}
					}
					else
					{
						//Printslab items
						$sql="select id_parent,title,price from prints where id_parent=".$dn->row["item"];
						$dr->open($sql);
						if(!$dr->eof)
						{
							$sql="select id,title,photo,id_parent from galleries_photos where id=".(int)$dn->row["printslab_id"];
							$ds->open($sql);
							if(!$ds->eof)
							{
								$title=$ds->row["title"];
								$preview=site_root."/content/galleries/".$ds->row["id_parent"]."/thumb".$ds->row["id"].".jpg";
								$url="../upload/index.php?d=7&id=".$ds->row["id_parent"];
							}
							?>
							<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
							<td class='preview_img'><a href="<?=$url?>"><img src="<?=$preview?>" border="0"></a></td>
							<td><a href="<?=$url?>"><b><?=word_lang("prints lab")?> #<?=$dn->row["printslab_id"]?> &mdash; <?=word_lang("prints")?>: <?=$dr->row["title"]?></b></a>
							<?
							for($i=1;$i<11;$i++)
							{
								if($dn->row["option".$i."_id"]!=0 and $dn->row["option".$i."_value"]!="")
								{
									$sql="select title from products_options where id=".$dn->row["option".$i."_id"];
									$ds->open($sql);
									if(!$ds->eof)
									{
										?><div class="gr"><?=$ds->row["title"]?>: <?=$dn->row["option".$i."_value"]?>.</div> <?
									}
								}
							}
							
							$price_total=define_prints_price($dn->row["price"],$dn->row["option1_id"],$dn->row["option1_value"],$dn->row["option2_id"],$dn->row["option2_value"],$dn->row["option3_id"],$dn->row["option3_value"],$dn->row["option4_id"],$dn->row["option4_value"],$dn->row["option5_id"],$dn->row["option5_value"],$dn->row["option6_id"],$dn->row["option6_value"],$dn->row["option7_id"],$dn->row["option7_value"],$dn->row["option8_id"],$dn->row["option8_value"],$dn->row["option9_id"],$dn->row["option9_value"],$dn->row["option10_id"],$dn->row["option10_value"]);
							?>
							</td>
							<td class="hidden-phone hidden-tablet"><span class="price"><?=currency(1,true,$method);?><?=float_opt($price_total,2)?> <?=currency(2,true,$method);?></span></td>
							<td class="hidden-phone hidden-tablet"><?=$dn->row["quantity"]?></td>
							<td><?=word_lang("shipping")?></td>
							</tr>
							<?
						}				
					}
				}
				else
				{
					//Stock items
					$sql="select id_parent,title,price from prints where id_parent=".$dn->row["item"];
					$dr->open($sql);
					if(!$dr->eof)
					{
						$title=@$mstocks[$dn->row["stock_type"]]." #".$dn->row["stock_id"];
						$preview=$dn->row["stock_preview"];
						$url=$dn->row["stock_site_url"];
						?>
						<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
						<td class='preview_img'><a href="<?=$url?>"><img src="<?=$preview?>" border="0" style="width:<?=$global_settings["thumb_width"]?>px"></a></td>
						<td><a href="<?=$url?>"><b><?=$title?> &mdash; <?=word_lang("prints")?>: <?=$dr->row["title"]?></b></a>
						<?
						for($i=1;$i<11;$i++)
						{
							if($dn->row["option".$i."_id"]!=0 and $dn->row["option".$i."_value"]!="")
							{
								$sql="select title from products_options where id=".$dn->row["option".$i."_id"];
								$ds->open($sql);
								if(!$ds->eof)
								{
									?><div class="gr"><?=$ds->row["title"]?>: <?=$dn->row["option".$i."_value"]?>.</div> <?
								}
							}
						}
						
						$price_total=define_prints_price($dn->row["price"],$dn->row["option1_id"],$dn->row["option1_value"],$dn->row["option2_id"],$dn->row["option2_value"],$dn->row["option3_id"],$dn->row["option3_value"],$dn->row["option4_id"],$dn->row["option4_value"],$dn->row["option5_id"],$dn->row["option5_value"],$dn->row["option6_id"],$dn->row["option6_value"],$dn->row["option7_id"],$dn->row["option7_value"],$dn->row["option8_id"],$dn->row["option8_value"],$dn->row["option9_id"],$dn->row["option9_value"],$dn->row["option10_id"],$dn->row["option10_value"]);
						?>
						</td>
						<td class="hidden-phone hidden-tablet"><span class="price"><?=currency(1,true,$method);?><?=float_opt($price_total,2)?> <?=currency(2,true,$method);?></span></td>
						<td class="hidden-phone hidden-tablet"><?=$dn->row["quantity"]?></td>
						<td><?=word_lang("shipping")?></td>
						</tr>
						<?
					}					
				}
			}
		$tr++;
		$dn->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
	
	<br>
	<h2><?=word_lang("comments")?>:</h2>
	<form method="post" action="comments_change.php?id=<?=(int)$_GET["id"]?>">
	<div class="admin_field">
	<textarea name="comments" style="width:600px;height:150px;"><?=$rs->row["comments"]?></textarea>
	</div>
	<div class="admin_field">
	<input type="submit" value="<?=word_lang("save")?>" class="btn btn-primary">
	</div>
	</form>
<?
}
?>



<? include("../inc/end.php");?>