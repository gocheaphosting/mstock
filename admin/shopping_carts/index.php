<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_carts");
?>
<? include("../inc/begin.php");?>



<a class="btn btn-danger toright" href="remove_all.php"><i class="icon-trash icon-white fa fa-remove"></i> <?=word_lang("remove all")?></a>

<h1><?=word_lang("shopping carts")?></h1>





<?
//Get Search
$search="";
if(isset($_GET["search"])){$search=result($_GET["search"]);}
if(isset($_POST["search"])){$search=result($_POST["search"]);}

//Get Search type
$search_type="";
if(isset($_GET["search_type"])){$search_type=result($_GET["search_type"]);}
if(isset($_POST["search_type"])){$search_type=result($_POST["search_type"]);}







//Items
$items=30;
if(isset($_GET["items"])){$items=(int)$_GET["items"];}
if(isset($_POST["items"])){$items=(int)$_POST["items"];}


//Search variable
$var_search="search=".$search."&search_type=".$search_type."&items=".$items;






//Sort by date
$adate=0;
if(isset($_GET["adate"])){$adate=(int)$_GET["adate"];}



//Sort by ID
$aid=0;
if(isset($_GET["aid"])){$aid=(int)$_GET["aid"];}

//Sort by default
if($adate==0 and $aid==0)
{
$adate=2;
}



//Add sort variable
$com="";


if($adate!=0)
{
	$var_sort="&adate=".$adate;
	if($adate==1){$com=" order by data ";}
	if($adate==2){$com=" order by data desc ";}
}



if($aid!=0)
{
	$var_sort="&aid=".$aid;
	if($aid==1){$com=" order by id ";}
	if($aid==2){$com=" order by id desc ";}
}








//Items on the page
$items_mass=array(10,20,30,50,75,100);




//Search parameter
$com2="";

if($search!="")
{
	if($search_type=="from")
	{
		$com2.=" and user_id=".user_url(result($search))." ";
	}
	if($search_type=="id")
	{
		$com2.=" and order_id=".(int)$search." ";
	}
	if($search_type=="ip")
	{
		$com2.=" and ip='".result($search)."' ";
	}
}





//Item's quantity
$kolvo=$items;


//Pages quantity
$kolvo2=k_str2;


//Page number
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}


$n=0;

$sql="select id,session_id,data,user_id,order_id,ip from carts where id>0 ";


$sql.=$com2.$com;

$rs->open($sql);
$record_count=$rs->rc;





//limit
$lm=" limit ".($kolvo*($str-1)).",".$kolvo;




$sql.=$lm;

//echo($sql);
$rs->open($sql);

?>
<div id="catalog_menu">


<form method="get" action="index.php" style="margin:0px">
<div class="toleft">
<span><?=word_lang("search")?>:</span>
<input type="text" name="search" style="width:200px;display:inline" class="ft" value="<?=$search?>" onClick="this.value=''">
<select name="search_type" style="width:150px;display:inline" class="ft">
<option value="from" <?if($search_type=="from"){echo("selected");}?>><?=word_lang("user")?></option>
<option value="id" <?if($search_type=="id"){echo("selected");}?>><?=word_lang("order")?> ID</option>
<option value="ip" <?if($search_type=="ip"){echo("selected");}?>>IP</option>
</select>
</div>

















<div class="toleft">
<span><?=word_lang("page")?>:</span>
<select name="items" style="width:70px" class="ft">
<?
for($i=0;$i<count($items_mass);$i++)
{
$sel="";
if($items_mass[$i]==$items){$sel="selected";}
?>
<option value="<?=$items_mass[$i]?>" <?=$sel?>><?=$items_mass[$i]?></option>
<?
}
?>

</select>
</div>

<div class="toleft">
<span>&nbsp;</span>
<input type="submit" class="btn btn-danger" value="<?=word_lang("search")?>">
</div>

<div class="toleft_clear"></div>
</form>


</div>



<?





if(!$rs->eof)
{
?>


<div style="padding:0px 9px 15px 6px"><?echo(paging($record_count,$str,$kolvo,$kolvo2,"index.php","&".$var_search.$var_sort));?></div>

<script language="javascript">
function publications_select_all(sel_form)
{
    if(sel_form.selector.checked)
   	{
        $("input:checkbox", sel_form).attr("checked",true);
    }
    else
    {
        $("input:checkbox", sel_form).attr("checked",false);
    }
}
</script>



<form method="post" action="delete.php" style="margin:0px"  id="adminform" name="adminform">
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
<tr>
<th><input type="checkbox"  name="selector" value="1" onClick="publications_select_all(document.adminform);"></th>
<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&adate=<?if($adate==2){echo(1);}else{echo(2);}?>"><?=word_lang("date")?></a> <?if($adate==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($adate==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>
</th>


<th><?=word_lang("order")?></th>
<th><?=word_lang("user")?></th>

<th width="50%"><?=word_lang("content")?></th>
<th class="hidden-phone hidden-tablet"><?=word_lang("total")?></th>

<th class="hidden-phone hidden-tablet"><?=word_lang("ip")?></th>



</tr>
<?
$tr=1;
while(!$rs->eof)
{
?>
<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
<td><input type="checkbox" name="sel<?=$rs->row["id"]?>" id="sel<?=$rs->row["id"]?>"></td>
<td class="gray hidden-phone hidden-tablet"><?=date(datetime_format,$rs->row["data"])?></td>

<td>
<?
if($rs->row["order_id"]!=0)
{
	?>
		<div class="link_order"><a href="../orders/order_content.php?id=<?=$rs->row["order_id"]?>"><?=word_lang("order")?> #<?=$rs->row["order_id"]?></a></div>
	<?
}
else
{
	?>
		<div class="link_status"><?=word_lang("not finished")?></div>
	<?
}
?>
</td>
<td>
<?
if($rs->row["user_id"]!=0)
{
	?>
		<div class="link_user"><a href="../customers/content.php?id=<?=$rs->row["user_id"]?>"><?=user_url_back($rs->row["user_id"])?></a></div>
	<?
}
else
{
	echo("&mdash;");
}
?>
</td>






<td>
	<?
		$total=0;
		$sql="select id,item_id,prints_id,publication_id,quantity,option1_id,option1_value,option2_id,option2_value,option3_id,option3_value,option4_id,option4_value,option5_id,option5_value,option6_id,option6_value,option7_id,option7_value,option8_id,option8_value,option9_id,option9_value,option10_id,option10_value,rights_managed,printslab,stock,stock_type,stock_id,stock_url,stock_preview,stock_site_url from carts_content where id_parent=".$rs->row["id"];
		$dr->open($sql);
		while(!$dr->eof)
		{
			if($dr->row["item_id"]>0)
			{				
				if($dr->row["rights_managed"]!="")
				{
					$rights_managed_price=0;
					$rights_mass=explode("|",$dr->row["rights_managed"]);
					$rights_managed_price=$rights_mass[0];
					
					$sql="select id,price,name from items where id=".$dr->row["item_id"];
					$ds->open($sql);
					if(!$ds->eof)
					{
						?>
							<div style="margin-bottom:3px"><a href="../catalog/content.php?id=<?=$dr->row["publication_id"]?>">#<?=$dr->row["publication_id"]?></a> &mdash; <?=$dr->row["quantity"]?> x <?=$ds->row["name"]?>  &mdash; <?=currency(1);?><?=float_opt($rights_managed_price,2,true)?>&nbsp;<?=currency(2);?></div>
						<?					
					}
					
					$total+=$rights_managed_price;
				}
				else
				{
					$sql="select id,price,name from items where id=".$dr->row["item_id"];
					$ds->open($sql);
					if(!$ds->eof)
					{
						?>
							<div style="margin-bottom:3px"><a href="../catalog/content.php?id=<?=$dr->row["publication_id"]?>">#<?=$dr->row["publication_id"]?></a> &mdash; <?=$dr->row["quantity"]?> x <?=$ds->row["name"]?>  &mdash; <?=currency(1);?><?=float_opt($ds->row["price"],2,true)?>&nbsp;<?=currency(2);?></div>
						<?					
						$total+=$ds->row["price"];
					}
				}
			}

			if($dr->row["prints_id"]>0)
			{
				if((int)$dr->row["stock"] == 0)
				{
					if($dr->row["printslab"]!=1)
					{
						$sql="select id_parent,price,title from prints_items where id_parent=".$dr->row["prints_id"];
						$ds->open($sql);
						if(!$ds->eof)
						{
							$price=define_prints_price($ds->row["price"],$dr->row["option1_id"],$dr->row["option1_value"],$dr->row["option2_id"],$dr->row["option2_value"],$dr->row["option3_id"],$dr->row["option3_value"],$dr->row["option4_id"],$dr->row["option4_value"],$dr->row["option5_id"],$dr->row["option5_value"],$dr->row["option6_id"],$dr->row["option6_value"],$dr->row["option7_id"],$dr->row["option7_value"],$dr->row["option8_id"],$dr->row["option8_value"],$dr->row["option9_id"],$dr->row["option9_value"],$dr->row["option10_id"],$dr->row["option10_value"]);
							?>
							<div style="margin-bottom:3px"><a href="../catalog/content.php?id=<?=$dr->row["publication_id"]?>">#<?=$dr->row["publication_id"]?></a> &mdash; <?=$dr->row["quantity"]?> x <?=$ds->row["title"]?> 
						
							<span class="gr">(<?
								for($i=1;$i<11;$i++)
								{
									if($dr->row["option".$i."_id"]!=0 and $dr->row["option".$i."_value"]!="")
									{
										$sql="select title from products_options where id=".$dr->row["option".$i."_id"];
										$dn->open($sql);
										if(!$dn->eof)
										{
											?><?=$dn->row["title"]?>: <?=$dr->row["option".$i."_value"]?>.<?
										}
									}
								}
							?>)</span> 
						
							&mdash; <?=currency(1);?><?=float_opt($price,2,true)?>&nbsp;<?=currency(2);?></div>
							<?
							$total+=$price*$dr->row["quantity"];
						}
					}
					else
					{
						$sql="select id_parent,price,title from prints where id_parent=".$dr->row["prints_id"];
						$ds->open($sql);
						if(!$ds->eof)
						{
							$price=define_prints_price($ds->row["price"],$dr->row["option1_id"],$dr->row["option1_value"],$dr->row["option2_id"],$dr->row["option2_value"],$dr->row["option3_id"],$dr->row["option3_value"],$dr->row["option4_id"],$dr->row["option4_value"],$dr->row["option5_id"],$dr->row["option5_value"],$dr->row["option6_id"],$dr->row["option6_value"],$dr->row["option7_id"],$dr->row["option7_value"],$dr->row["option8_id"],$dr->row["option8_value"],$dr->row["option9_id"],$dr->row["option9_value"],$dr->row["option10_id"],$dr->row["option10_value"]);
							
							$gallery_id=0;
							$sql="select id_parent from galleries_photos where id=".$dr->row["publication_id"];
							$dn->open($sql);
							if(!$dn->eof)
							{
								$gallery_id=$dn->row["id_parent"];
							}
							?>
							<div style="margin-bottom:3px"><a href="../upload/index.php?d=7&id=<?=$gallery_id?>">#<?=$dr->row["publication_id"]?></a> &mdash; <?=$dr->row["quantity"]?> x <?=$ds->row["title"]?> 
						
							<span class="gr">(<?
								for($i=1;$i<11;$i++)
								{
									if($dr->row["option".$i."_id"]!=0 and $dr->row["option".$i."_value"]!="")
									{
										$sql="select title from products_options where id=".$dr->row["option".$i."_id"];
										$dn->open($sql);
										if(!$dn->eof)
										{
											?><?=$dn->row["title"]?>: <?=$dr->row["option".$i."_value"]?>.<?
										}
									}
								}
							?>)</span> 
						
							&mdash; <?=currency(1);?><?=float_opt($price,2,true)?>&nbsp;<?=currency(2);?></div>
							<?
							$total+=$price*$dr->row["quantity"];
						}				
					}
				}
				else
				{
					$sql="select id_parent,price,title from prints where id_parent=".$dr->row["prints_id"];
					$ds->open($sql);
					if(!$ds->eof)
					{
						$price=define_prints_price($ds->row["price"],$dr->row["option1_id"],$dr->row["option1_value"],$dr->row["option2_id"],$dr->row["option2_value"],$dr->row["option3_id"],$dr->row["option3_value"],$dr->row["option4_id"],$dr->row["option4_value"],$dr->row["option5_id"],$dr->row["option5_value"],$dr->row["option6_id"],$dr->row["option6_value"],$dr->row["option7_id"],$dr->row["option7_value"],$dr->row["option8_id"],$dr->row["option8_value"],$dr->row["option9_id"],$dr->row["option9_value"],$dr->row["option10_id"],$dr->row["option10_value"]);
						
						$title=@$mstocks[$dr->row["stock_type"]]." #".$dr->row["stock_id"];
						$preview=$dr->row["stock_preview"];
						$url=$dr->row["stock_site_url"];
						?>
						<div style="margin-bottom:3px"><a href="<?=$url?>"><?=$title?></a> &mdash; <?=$dr->row["quantity"]?> x <?=$ds->row["title"]?> 
					
						<span class="gr">(<?
							for($i=1;$i<11;$i++)
							{
								if($dr->row["option".$i."_id"]!=0 and $dr->row["option".$i."_value"]!="")
								{
									$sql="select title from products_options where id=".$dr->row["option".$i."_id"];
									$dn->open($sql);
									if(!$dn->eof)
									{
										?><?=$dn->row["title"]?>: <?=$dr->row["option".$i."_value"]?>.<?
									}
								}
							}
						?>)</span> 
					
						&mdash; <?=currency(1);?><?=float_opt($price,2,true)?>&nbsp;<?=currency(2);?></div>
						<?
						$total+=$price*$dr->row["quantity"];
					}				
				}
			}
			$dr->movenext();
		}
	?>
</td>
<td class="hidden-phone hidden-tablet"><b><?=currency(1);?><?=float_opt($total,2,true)?>&nbsp;<?=currency(2);?></b></td>
<td class="hidden-phone hidden-tablet"><div class="link_ip"><?=$rs->row["ip"]?></div></td>

</tr>
<?
$n++;
$tr++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>


<input type="submit" class="btn btn-danger" value="<?=word_lang("delete")?>"  style="margin:15px 0px 0px 6px;">






</form>
<div style="padding:25px 0px 0px 6px;"><?echo(paging($record_count,$str,$kolvo,$kolvo2,"index.php","&".$var_search.$var_sort));?></div>
<?
}
else
{
echo("<p><b>".word_lang("not found")."</b></p>");
}
?>

<? include("../inc/end.php");?>