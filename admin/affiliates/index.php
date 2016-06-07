<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("affiliates_stats");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>



<h1><?=word_lang("affiliates")?>:</h1>








<?
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
$var_search="search=".$search."&search_type=".$search_type."&items=".$items."&pub_type=".$pub_type;






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












//Items on the page
$items_mass=array(10,20,30,50,75,100);




//Search parameter
$com2="";

if($search!="")
{

	if($search_type=="id")
	{
		$com2.=" and types_id=".(int)$search." ";
	}
	if($search_type=="user")
	{
		$com2.=" and userid = ".user_url($search)." ";
	}
	if($search_type=="affiliate")
	{
		$com2.=" and aff_referal = ".user_url($search)." ";
	}

}


if($pub_type=="order")
{
	$com2.=" and types='orders' ";
}
if($pub_type=="credits")
{
	$com2.=" and types='credits' ";
}
if($pub_type=="subscription")
{
	$com2.=" and types='subscription' ";
}


//Item's quantity
$kolvo=$items;


//Pages quantity
$kolvo2=k_str2;


//Page number
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}


$n=0;

$sql="select userid,types,types_id,rates,total,data,aff_referal from affiliates_signups where total>0 ";


$sql.=$com2.$com;
//echo($sql);
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
<option value="affiliate" <?if($search_type=="affiliate"){echo("selected");}?>><?=word_lang("affiliate")?></option>
<option value="user" <?if($search_type=="user"){echo("selected");}?>><?=word_lang("user")?></option>
<option value="id" <?if($search_type=="id"){echo("selected");}?>>Order ID</option>

</select>
</div>










<div class="toleft">
<span><?=word_lang("type")?>:</span>
<select name="pub_type" style="width:150px" class="ft">
<option value="all"><?=word_lang("all")?></option>
<option value="order" <?if($pub_type=="order"){echo("selected");}?>><?=word_lang("order")?></option>
<option value="credits" <?if($pub_type=="credits"){echo("selected");}?>><?=word_lang("credits")?></option>
<option value="subscription" <?if($pub_type=="subscription"){echo("selected");}?>><?=word_lang("subscription")?></option>

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


<div style="padding: 0px 0px 15px 6px"><?echo(paging($record_count,$str,$kolvo,$kolvo2,"index.php","&".$var_search.$var_sort));?></div>


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
<th><?=word_lang("affiliate")?></th>
<th><?=word_lang("total")?></th>
<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&adate=<?if($adate==2){echo(1);}else{echo(2);}?>"><?=word_lang("date")?></a> <?if($adate==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($adate==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>

</th>
<th class="hidden-phone hidden-tablet"><?=word_lang("title")?></th>
<th><?=word_lang("user")?></th>




	
</tr>
<?
$tr=1;
while(!$rs->eof)
{
?>
<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>


<td><input type="checkbox" name="sel<?=$rs->row["userid"]?>_<?=$rs->row["data"]?>_<?=$rs->row["types_id"]?>"></td>

<td><div class="link_user"><a href="../customers/content.php?id=<?=$rs->row["aff_referal"]?>"><?=user_url_back($rs->row["aff_referal"])?></a></div></td>
<td><span class="price"><b><?=currency(1,true,"credit");?><?=float_opt($rs->row["total"],2)?> <?=currency(2,true,"credit");?></b></span> (<?=$rs->row["rates"]?>%)</td>
<td class="gray hidden-phone hidden-tablet"><?=date(date_short,$rs->row["data"])?></td>
<td class="hidden-phone hidden-tablet"><?=word_lang($rs->row["types"])?> #<?=$rs->row["types_id"]?></td>
<td>

<div class="link_user"><a href="../customers/content.php?id=<?=$rs->row["userid"]?>"><?=user_url_back($rs->row["userid"])?></a></div>


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


<input type="submit" value="<?=word_lang("delete")?>" style="margin:10px 0px 0px 6px" class="btn btn-danger">


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