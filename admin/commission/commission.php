<?
//Check access
admin_panel_access("orders_commission");
?>







<script type="text/javascript" language="JavaScript" src="../../members/JsHttpRequest.js"></script>
<script type="text/javascript" language="JavaScript">



function deselect_row(value)
{
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
			$("#"+value).removeClass("success");
        }
    }
    req.open(null, '<?=site_root?>/admin/inc/deselect.php', true);
    req.send( {'id': value} );
}
</script>








<?
//Get Search
$search="";
if(isset($_GET["search"])){$search=result($_GET["search"]);}
if(isset($_POST["search"])){$search=result($_POST["search"]);}

//Get Search type
$search_type="";
if(isset($_GET["search_type"])){$search_type=result($_GET["search_type"]);}
if(isset($_POST["search_type"])){$search_type=result($_POST["search_type"]);}


//Get commission type
$commission_type="";
if(isset($_GET["commission_type"])){$commission_type=result($_GET["commission_type"]);}
if(isset($_POST["commission_type"])){$commission_type=result($_POST["commission_type"]);}


//Get pub_type
$pub_type="all";
if(isset($_GET["pub_type"])){$pub_type=result($_GET["pub_type"]);}
if(isset($_POST["pub_type"])){$pub_type=result($_POST["pub_type"]);}




//Items
$items=30;
if(isset($_GET["items"])){$items=(int)$_GET["items"];}
if(isset($_POST["items"])){$items=(int)$_POST["items"];}


//Search variable
$var_search="&d=1&search=".$search."&search_type=".$search_type."&items=".$items."&pub_type=".$pub_type."&commission_type=".$commission_type;






//Sort by date
$adate=2;
if(isset($_GET["adate"])){$adate=(int)$_GET["adate"];}






//Add sort variable
$com="";


if($adate!=0)
{
	$var_sort="&adate=".$adate;
	if($adate==1){$com=" order by data ";}
	if($adate==2){$com=" order by data desc ";}
}







//Items on the page
$items_mass=array(10,20,30,50,75,100);




//Search parameter
$com2="";

if($search!="")
{

	if($search_type=="id")
	{
		$com2.=" and orderid=".(int)$search." ";
	}
	if($search_type=="login")
	{
		$com2.=" and user = ".user_url($search);
	}
	if($search_type=="id_file")
	{
		$com2.=" and publication = '".(int)$search."' ";
	}

}


if($pub_type=="plus")
{
	$com2.=" and total>0 ";
}
if($pub_type=="minus")
{
	$com2.=" and total<0 ";
}


if($commission_type=="order")
{
	$com2.=" and description='order' ";
}
if($commission_type=="subscription")
{
	$com2.=" and description='subscription' ";
}





//Item's quantity
$kolvo=$items;


//Pages quantity
$kolvo2=k_str2;


//Page number
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}


$n=0;

$sql="select id,total,user,orderid,item,publication,types,data,gateway,description from commission where status=1 ";


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


<form method="get" action="index.php?d=1" style="margin:0px">
<div class="toleft">
<span><?=word_lang("search")?>:</span>
<input type="text" name="search" style="width:200px;display:inline" class="ft" value="<?=$search?>" onClick="this.value=''">
<select name="search_type" style="width:100px;display:inline" class="ft">
<option value="login" <?if($search_type=="login"){echo("selected");}?>><?=word_lang("login")?></option>
<option value="id" <?if($search_type=="id"){echo("selected");}?>><?=word_lang("order")?> ID</option>
<option value="id_file" <?if($search_type=="id_file"){echo("selected");}?>><?=word_lang("file")?> ID</option>

</select>
</div>










<div class="toleft">
<span><?=word_lang("total")?>:</span>
<select name="pub_type" style="width:150px" class="ft">
<option value="all"><?=word_lang("all")?></option>
<option value="plus" <?if($pub_type=="plus"){echo("selected");}?>>+ <?=word_lang("earning")?></option>
<option value="minus" <?if($pub_type=="minus"){echo("selected");}?>>- <?=word_lang("refund")?></option>
</select>
</div>


<div class="toleft">
<span><?=word_lang("type")?>:</span>
<select name="commission_type" style="width:200px" class="ft">
<option value=""><?=word_lang("all")?></option>
<option value="order" <?if($commission_type=="order"){echo("selected");}?>><?=word_lang("order")?></option>
<option value="subscription" <?if($commission_type=="subscription"){echo("selected");}?>><?=word_lang("subscription")?></option>
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


<div style="padding:0px 0px 15px 6px"><?echo(paging($record_count,$str,$kolvo,$kolvo2,"index.php","&".$var_search.$var_sort));?></div>

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



<form method="post" action="commission_delete.php" style="margin:0px"  id="adminform" name="adminform">
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
<tr>
<th><input type="checkbox"  name="selector" value="1" onClick="publications_select_all(document.adminform);"></th>
<th><?=word_lang("order")?> ID</th>
<th><?=word_lang("seller")?></th>
<th><?if($commission_type!="minus"){echo(word_lang("commission"));}?><?if($commission_type==""){echo(" / ");}?><?if($commission_type!="plus"){echo(word_lang("refund"));}?></th>



<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&adate=<?if($adate==2){echo(1);}else{echo(2);}?>"><?=word_lang("date")?></a> <?if($adate==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($adate==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>
</th>


<th class="hidden-phone hidden-tablet"><?=word_lang("file")?> ID</th>


</tr>
<?
$tr=1;
while(!$rs->eof)
{

$cl3="";
$cl_script="";
if(isset($_SESSION["user_commission_id"]) and !isset($_SESSION["admin_rows_commission".$rs->row["id"]]) and $rs->row["id"]>$_SESSION["user_commission_id"])
{
	$cl3="success";	
	$cl_script="onMouseover=\"deselect_row('commission".$rs->row["id"]."')\"";
}
?>
<tr id="commission<?=$rs->row["id"]?>" class='<?if($tr%2==0){echo("snd");}?> <?=$cl3?>' valign="top" <?=$cl_script?>>
<td><input type="checkbox" name="sel<?=$rs->row["id"]?>" id="sel<?=$rs->row["id"]?>"></td>
<td>
<?
if($rs->row["total"]>0)
{
	if($rs->row["description"]=="subscription")
	{
		?>
		<div class="link_order"><a href="../subscription_list/edit.php?id=<?=$rs->row["orderid"]?>"><?=word_lang("subscription")?> # <?=$rs->row["orderid"]?></a></div>
		<?
	}
	else
	{
		?>
		<div class="link_order"><a href="../orders/order_content.php?id=<?=$rs->row["orderid"]?>"><?=word_lang("order")?> # <?=$rs->row["orderid"]?></a></div>
		<?
	}
}
else
{
	echo("<div class='link_payout'>".word_lang("refund")."</div>");
}
?>
</td>

<td><div class="link_user"><a href="../customers/content.php?id=<?=$rs->row["user"]?>"><?=user_url_back($rs->row["user"])?></a></div></td>

<td><span class="price"><b><?=currency(1,true,"credit");?><?=float_opt($rs->row["total"],2)?> <?=currency(2,true,"credit");?></b></span></td>


<td class="gray hidden-phone hidden-tablet"><?=date(date_format,$rs->row["data"])?></td>

<td class="hidden-phone hidden-tablet">
<?
if($rs->row["total"]>0)
{
	?>
	<div class="link_file"><a href="../catalog/content.php?id=<?=$rs->row["publication"]?>"><?=$rs->row["publication"]?></a></div>
	<?
}
else
{
	?>
	&mdash;
	<?
}
?>
</td>


</tr>
<?
$tr++;
$n++;
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































