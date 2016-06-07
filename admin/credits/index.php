<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_credits");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>



<a class="btn btn-success toright" href="new.php"><i class="icon-tags icon-white fa fa-plus"></i> <?=word_lang("credits")?></a>

<h1><?=word_lang("Credits list")?>:</h1>



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





//Get pub_type
$pub_type="all";
if(isset($_GET["pub_type"])){$pub_type=result($_GET["pub_type"]);}
if(isset($_POST["pub_type"])){$pub_type=result($_POST["pub_type"]);}


//Get credits type
$credits_type=0;
if(isset($_GET["credits_type"])){$credits_type=(int)$_GET["credits_type"];}
if(isset($_POST["credits_type"])){$credits_type=(int)$_POST["credits_type"];}


//Items
$items=30;
if(isset($_GET["items"])){$items=(int)$_GET["items"];}
if(isset($_POST["items"])){$items=(int)$_POST["items"];}


//Search variable
$var_search="search=".$search."&search_type=".$search_type."&items=".$items."&pub_type=".$pub_type."&credits_type=".$credits_type;






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
	if($aid==1){$com=" order by id_parent ";}
	if($aid==2){$com=" order by id_parent desc ";}
}








//Items on the page
$items_mass=array(10,20,30,50,75,100);




//Search parameter
$com2="";

if($search!="")
{

	if($search_type=="id")
	{
		$com2.=" and id_parent=".(int)$search." ";
	}
	if($search_type=="login")
	{
		$com2.=" and user = '".$search."' ";
	}

}


if($pub_type=="plus")
{
	$com2.=" and quantity>0 ";
}
if($pub_type=="minus")
{
	$com2.=" and quantity<0 ";
}

if($credits_type!=0)
{
	$com2.=" and credits=".$credits_type." ";
}


//Item's quantity
$kolvo=$items;


//Pages quantity
$kolvo2=k_str2;


//Page number
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}


$n=0;

$sql="select id_parent,title,quantity,user,data,approved,expiration_date,total from credits_list where id_parent>0 ";


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
<select name="search_type" style="width:100px;display:inline" class="ft">
<option value="login" <?if($search_type=="login"){echo("selected");}?>><?=word_lang("login")?></option>
<option value="id" <?if($search_type=="id"){echo("selected");}?>>ID</option>

</select>

</div>










<div class="toleft">
<span><?=word_lang("type")?>:</span>
<select name="pub_type" style="width:100px" class="ft">
<option value="all"><?=word_lang("all")?></option>
<option value="plus" <?if($pub_type=="plus"){echo("selected");}?>>+ Credits</option>
<option value="minus" <?if($pub_type=="minus"){echo("selected");}?>>- Credits</option>

</select>
</div>


<div class="toleft">
<span><?=word_lang("credits types")?>:</span>
<select name="credits_type" style="width:200px" class="ft">
<option value="0"><?=word_lang("all")?></option>
<?
$sql="select id_parent,title from credits order by priority";
$ds->open($sql);
while(!$ds->eof)
{
	$sel="";
	if($credits_type==$ds->row["id_parent"])
	{
		$sel="selected";
	}
	?><option value="<?=$ds->row["id_parent"]?>" <?=$sel?>><?=$ds->row["title"]?></option><?
	$ds->movenext();
}
?>
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



<form method="post" action="delete.php" style="margin:0px"  id="adminform" name="adminform">
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover" width="100%">
<tr>
<th><input type="checkbox"  name="selector" value="1" onClick="publications_select_all(document.adminform);"></th>
<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&aid=<?if($aid==2){echo(1);}else{echo(2);}?>">ID</a> <?if($aid==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($aid==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>
</th>
<th class="hidden-phone hidden-tablet"><?=word_lang("order")?></th>
<th><?=word_lang("quantity")?></th>
<th><?=word_lang("user")?></th>


<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&adate=<?if($adate==2){echo(1);}else{echo(2);}?>"><?=word_lang("date")?></a> <?if($adate==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($adate==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>

</th>
<th class="hidden-phone hidden-tablet"><?=word_lang("expiration date")?></th>

<th><?=word_lang("status")?></th>



<th class="hidden-phone hidden-tablet"><?=word_lang("Invoice")?></th>


</tr>
<?
$tr=1;
while(!$rs->eof)
{

$cl3="";
$cl_script="";
if(isset($_SESSION["user_credits_id"]) and !isset($_SESSION["admin_rows_credits".$rs->row["id_parent"]]) and $rs->row["id_parent"]>$_SESSION["user_credits_id"] and $_SESSION["user_credits_id"]>0)
{
	$cl3="success";	
	$cl_script="onMouseover=\"deselect_row('credits".$rs->row["id_parent"]."')\"";
}
?>
<tr id="credits<?=$rs->row["id_parent"]?>" class='<?if($tr%2==0){echo("snd");}?> <?=$cl3?>' valign="top" <?=$cl_script?>>
<td><input type="checkbox" name="sel<?=$rs->row["id_parent"]?>" id="sel<?=$rs->row["id_parent"]?>"></td>
<td class="hidden-phone hidden-tablet"><?=$rs->row["id_parent"]?></td>
<td class="big hidden-phone hidden-tablet"><?=$rs->row["title"]?></td>
<td><?=$rs->row["quantity"]?></td>
<td>

<div class="link_user"><a href="../customers/content.php?id=<?=user_url($rs->row["user"])?>"><?=$rs->row["user"]?></a></div>


</td>

<td class="gray hidden-phone hidden-tablet"><?=date(date_short,$rs->row["data"])?></td>

<td class="gray hidden-phone hidden-tablet">
<?
if($rs->row["quantity"]>0)
{
	if($rs->row["expiration_date"]==0)
	{
		echo(word_lang("never"));
	}
	else
	{
		echo(date(date_short,$rs->row["expiration_date"]));
	}
}
else
{
	echo("&#8212;");
}
?>
</td>

<td>
<?
if($rs->row["quantity"]>0)
{

$cl="success";
if($rs->row["approved"]!=1)
{
$cl="danger";
}




?>
<div id="status<?=$rs->row["id_parent"]?>" name="status<?=$rs->row["id_parent"]?>"><a href="javascript:doLoad(<?=$rs->row["id_parent"]?>);"><span class="label label-<?=$cl?>"><?if($rs->row["approved"]==1){echo(word_lang("approved"));}else{echo(word_lang("pending"));}?></span></a></div>





<?
}
else
{
	echo("&#8212;");
}
?>

</td>


<td class="hidden-phone hidden-tablet">
<?
if($rs->row["quantity"]>0 and $rs->row["total"]>0)
{
	$invoice_number = "";
	$link_class = "";
	
	$sql="select invoice_number,refund from invoices where order_type='credits' and order_id=".$rs->row["id_parent"]." order by id";
	$ds->open($sql);
	while(!$ds->eof)
	{	
		if($ds->row["refund"] == 1)
		{
			$link_class = "class='red'";
			$invoice_number = "#".$global_settings["credit_notes_prefix"].$ds->row["invoice_number"];
			$word_refund = word_lang("Refund money").": ";
		}
		else
		{
			$invoice_number = "#".$global_settings["invoice_prefix"].$ds->row["invoice_number"];
			$word_refund = "";
		}
		?>
		<a href="../invoices/invoice.php?id=<?=$ds->row["invoice_number"]?>" <?=$link_class?>><i class="fa fa-file-pdf-o"></i>
		 <?=$word_refund?><?=$invoice_number?></a><br>
		<?
		$ds->movenext();
	}
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

<? include("../inc/end.php");?>