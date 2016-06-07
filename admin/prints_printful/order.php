<?
//Check access
admin_panel_access("settings_printful");

if(!defined("site_root")){exit();}



//Get Search
$search="";
if(isset($_GET["search"])){$search=result($_GET["search"]);}
if(isset($_POST["search"])){$search=result($_POST["search"]);}

//Get Search type
$search_type="";
if(isset($_GET["search_type"])){$search_type=result($_GET["search_type"]);}
if(isset($_POST["search_type"])){$search_type=result($_POST["search_type"]);}





//Get pub_type
$pub_type="all";
if(isset($_GET["pub_type"])){$pub_type=result($_GET["pub_type"]);}
if(isset($_POST["pub_type"])){$pub_type=result($_POST["pub_type"]);}


//Items
$items=30;
if(isset($_GET["items"])){$items=(int)$_GET["items"];}
if(isset($_POST["items"])){$items=(int)$_POST["items"];}


//Search variable
$var_search="search=".$search."&search_type=".$search_type."&items=".$items."&pub_type=".$pub_type."&d=2";








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

	if($search_type=="id")
	{
		$com2.=" and id=".(int)$search." ";
	}
	if($search_type=="login")
	{
		$com2.=" and user = '".user_url($search)."' ";
	}

}


if($pub_type=="approved")
{
	$com2.=" and status=1 ";
}
if($pub_type=="pending")
{
	$com2.=" and status=0 ";
}


//Item's quantity
$kolvo=$items;


//Pages quantity
$kolvo2=k_str2;


//Page number
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}


$n=0;

$order_number=0;
$sql="select order_number from printful";
$rs->open($sql);
if(!$rs->eof)
{
	$order_number=$rs->row["order_number"];
}

$sql="select * from orders where status=1 and id>".$order_number." ";


$sql.=$com2.$com;







//limit
$lm=" limit ".($kolvo*($str-1)).",".$kolvo;




$sql.=$lm;

//echo($sql);
$rs->open($sql);

?>
<div id="catalog_menu">


<form method="get" action="index.php" style="margin:0px">
<input type="hidden" name="d" value="2">
<div class="toleft">
<span><?=word_lang("search")?>:</span>
<input type="text" name="search" style="width:200px;display:inline" class="ft" value="<?=$search?>" onClick="this.value=''">
<select name="search_type" style="width:100px;display:inline" class="ft">
<option value="login" <?if($search_type=="login"){echo("selected");}?>><?=word_lang("login")?></option>
<option value="id" <?if($search_type=="id"){echo("selected");}?>>ID</option>

</select>

</div>















<div class="toleft">
<span><?=word_lang("page")?>:</span>
<select name="items" style="width:80px" class="ft">
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




$tr=1;
if(!$rs->eof)
{
?>



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



<form method="post" action="send.php" style="margin:0px"  id="adminform" name="adminform">
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover" width="100%">
<tr>
<th><input type="checkbox"  name="selector" value="1" onClick="publications_select_all(document.adminform);"></th>
<th>
<a href="index.php?<?=$var_search?>&aid=<?if($aid==2){echo(1);}else{echo(2);}?>">ID</a> <?if($aid==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($aid==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>
</th>
<th>
<a href="index.php?<?=$var_search?>&adate=<?if($adate==2){echo(1);}else{echo(2);}?>"><?=word_lang("date")?></a> <?if($adate==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($adate==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>
</th>

<th><?=word_lang("user")?></b></th>
<th><?=word_lang("content")?></b></th>
<th><?=word_lang("total")?></b></th>
<th>Sent to printful</th>
</tr>








<?
while(!$rs->eof)
{
	$flag_prints=false;
	$content="";
	$total=0;
	
	$sql="select item,price,quantity,option1_id,option1_value,option2_id,option2_value,option3_id,option3_value,option4_id,option4_value,option5_id,option5_value,option6_id,option6_value,option7_id,option7_value,option8_id,option8_value,option9_id,option9_value,option10_id,option10_value,printslab,printslab_id,prints from orders_content where prints=1 and id_parent=".$rs->row["id"];
	$ds->open($sql);
	while(!$ds->eof)
	{
		$printful_id=0;
		
		if($ds->row["prints"]==1)
		{
			if($ds->row["printslab"]!=1)
			{
				$sql="select printsid,itemid,title from prints_items where id_parent=".$ds->row["item"];
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="select printful_id from printful_prints where print_id=".$dr->row["printsid"]." and option1=".$ds->row["option1_id"]." and option1_value='".$ds->row["option1_value"]."'  and option2=".$ds->row["option2_id"]." and option2_value='".$ds->row["option2_value"]."'  and option3=".$ds->row["option3_id"]." and option3_value='".$ds->row["option3_value"]."'  and option4=".$ds->row["option4_id"]." and option4_value='".$ds->row["option4_value"]."'  and option5=".$ds->row["option5_id"]." and option5_value='".$ds->row["option5_value"]."'  and option6=".$ds->row["option6_id"]." and option6_value='".$ds->row["option6_value"]."'  and option7=".$ds->row["option7_id"]." and option7_value='".$ds->row["option7_value"]."'  and option8=".$ds->row["option8_id"]." and option8_value='".$ds->row["option8_value"]."'  and option9=".$ds->row["option9_id"]." and option9_value='".$ds->row["option9_value"]."'  and option10=".$ds->row["option10_id"]." and option10_value='".$ds->row["option10_value"]."' ";
					$dn->open($sql);
					if(!$dn->eof)
					{
						$printful_id=$dn->row["printful_id"];
					}
				}
			}
			else
			{
				$sql="select printful_id from printful_prints where print_id=".$ds->row["item"]." and option1=".$ds->row["option1_id"]." and option1_value='".$ds->row["option1_value"]."'  and option2=".$ds->row["option2_id"]." and option2_value='".$ds->row["option2_value"]."'  and option3=".$ds->row["option3_id"]." and option3_value='".$ds->row["option3_value"]."'  and option4=".$ds->row["option4_id"]." and option4_value='".$ds->row["option4_value"]."'  and option5=".$ds->row["option5_id"]." and option5_value='".$ds->row["option5_value"]."'  and option6=".$ds->row["option6_id"]." and option6_value='".$ds->row["option6_value"]."'  and option7=".$ds->row["option7_id"]." and option7_value='".$ds->row["option7_value"]."'  and option8=".$ds->row["option8_id"]." and option8_value='".$ds->row["option8_value"]."'  and option9=".$ds->row["option9_id"]." and option9_value='".$ds->row["option9_value"]."'  and option10=".$ds->row["option10_id"]." and option10_value='".$ds->row["option10_value"]."' ";
				$dn->open($sql);
				if(!$dn->eof)
				{
					$printful_id=$dn->row["printful_id"];
				}
			}
		}
				
		if($ds->row["prints"]==1 and $printful_id!=0)
		{
			if($ds->row["printslab"]!=1)
			{
				$sql="select printsid,itemid,title from prints_items where id_parent=".$ds->row["item"];
				$dr->open($sql);
				if(!$dr->eof)
				{
					$flag_prints=true;
			
					$content.="<div style='margin-bottom:3px'><a href='../catalog/content.php?id=".$dr->row["itemid"]."'>#".$dr->row["itemid"]."</a> &mdash; ".$ds->row["quantity"]." x ".$dr->row["title"]." <span class='gr'>(";
			
					for($i=1;$i<11;$i++)
					{
						if($ds->row["option".$i."_id"]!=0 and $ds->row["option".$i."_value"]!="")
						{
							$sql="select title from products_options where id=".$ds->row["option".$i."_id"];
							$dn->open($sql);
							if(!$dn->eof)
							{
								$content.=$dn->row["title"].": ".$ds->row["option".$i."_value"].". ";
							}
						}
					}
			
					$content.=")</span></div>";
			
					$total+=$ds->row["price"]*$ds->row["quantity"];
				}
			}
			else
			{
				$sql="select id_parent,title from prints where id_parent=".$ds->row["item"];
				$dr->open($sql);
				if(!$dr->eof)
				{
					$flag_prints=true;
					
					$gallery_id=0;
					$sql="select id_parent from galleries_photos where id=".$ds->row["printslab_id"];
					$dn->open($sql);
					if(!$dn->eof)
					{
						$gallery_id=$dn->row["id_parent"];
					}
			
					$content.="<div style='margin-bottom:3px'><a href='../upload/index.php?d=7&id=".$gallery_id."'>".word_lang("prints lab")." #".$ds->row["printslab_id"]."</a> &mdash; ".$ds->row["quantity"]." x ".$dr->row["title"]." <span class='gr'>(";
			
					for($i=1;$i<11;$i++)
					{
						if($ds->row["option".$i."_id"]!=0 and $ds->row["option".$i."_value"]!="")
						{
							$sql="select title from products_options where id=".$ds->row["option".$i."_id"];
							$dn->open($sql);
							if(!$dn->eof)
							{
								$content.=$dn->row["title"].": ".$ds->row["option".$i."_value"].". ";
							}
						}
					}
			
					$content.=")</span></div>";
			
					$total+=$ds->row["price"]*$ds->row["quantity"];
				}			
			}
		}
		
		$ds->movenext();
	}
	
	if($flag_prints)
	{
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
		
			<td><input type="checkbox" name="sel<?=$rs->row["id"]?>" id="sel<?=$rs->row["id"]?>"></td>
			
			<td class="big"><div class="link_order"><a href="../orders/order_content.php?id=<?=$rs->row["id"]?>"><b><?=word_lang("order")?> #<?=$rs->row["id"]?></b></a></div></td>
			
			<td class="gray"><?=date(date_format,$rs->row["data"])?></td>

			<td>
				<?
				$sql="select id_parent,login from users where id_parent=".$rs->row["user"];
				$ds->open($sql);
				if(!$ds->eof)
				{
					?>
					<div class="link_user"><a href="../customers/content.php?id=<?=$ds->row["id_parent"]?>"><?=$ds->row["login"]?></a></div>
					<?
				}
				?>
			</td>
			
			<td><?=$content?></td>
			
			<td><b><?=currency(1);?><?=float_opt($total,2)?> <?=currency(2);?></b></td>
			
			<td>
				<?
				$sql="select data,printful_id from printful_orders where order_id=".$rs->row["id"];
				$ds->open($sql);
				if(!$ds->eof)
				{
					echo("<div class='gray'><b>".word_lang("date").":</b> ".date(date_format,$ds->row["data"])."</div>");
					
					echo("<div class='gray'><b>printful order ID:</b> ".$ds->row["printful_id"]."</div>");
				}
				else
				{
					echo(word_lang("no"));
				}
				?>
			</td>
		</tr>
		<?
		$tr++;
		$n++;
	}

	$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>





<div style="padding:25px 0px 10px 5px;"><?echo(paging($n,$str,$kolvo,$kolvo2,"index.php","&".$var_search.$var_sort));?></div>
<?
}
else
{
echo("<p><b>".word_lang("not found")."</b></p>");
}

?>

<div id="button_bottom_static">
	<div id="button_bottom_layout"></div>
	<div id="button_bottom">
		<input type="submit" class="btn btn-primary" value="Send to printful">
	</div>
</div>
</form>