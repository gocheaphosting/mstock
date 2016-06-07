<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_subscription");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>



<a class="btn btn-success toright" href="new.php"><i class="icon-calendar icon-white fa fa-clock-o"></i>&nbsp; <?=word_lang("subscription")?></a>

<h1><?=word_lang("Subscription list")?>:</h1>



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
$subscription_limit="Credits";
$sql="select name from subscription_limit where activ=1";
$rs->open($sql);
if(!$rs->eof)
{
	$subscription_limit=$rs->row["name"];
}
?>


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
	if($adate==1){$com=" order by data1 ";}
	if($adate==2){$com=" order by data1 desc ";}
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


if($pub_type=="activ")
{
	$com2.=" and data2>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." ";
}
if($pub_type=="expired")
{
	$com2.=" and data2<".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." ";
}


//Item's quantity
$kolvo=$items;


//Pages quantity
$kolvo2=k_str2;


//Page number
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}


$n=0;

$sql="select id_parent,title,user,data1,data2,approved,bandwidth,bandwidth_limit,payments,bandwidth_daily,bandwidth_daily_limit,total from subscription_list where id_parent>0 ";


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
<select name="search_type" style="width:100px;display:inline" class="ft">
<option value="login" <?if($search_type=="login"){echo("selected");}?>><?=word_lang("login")?></option>
<option value="id" <?if($search_type=="id"){echo("selected");}?>>ID</option>

</select>
</div>










<div class="toleft">
<span><?=word_lang("type")?>:</span>
<select name="pub_type" style="width:100px" class="ft">
<option value="all"><?=word_lang("all")?></option>
<option value="activ" <?if($pub_type=="activ"){echo("selected");}?>>Active</option>
<option value="expired" <?if($pub_type=="expired"){echo("selected");}?>>Expired</option>

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
<th><?=word_lang("title")?></th>
<th><?=word_lang("user")?></th>


<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&adate=<?if($adate==2){echo(1);}else{echo(2);}?>"><?=word_lang("setup date")?></a> <?if($adate==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($adate==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>

</th>
<th class="hidden-phone hidden-tablet"><?=word_lang("expiration date")?></th>
<th class="hidden-phone hidden-tablet"><?=word_lang("limit")?> (<?=$subscription_limit?><?if($subscription_limit=="Bandwidth"){echo(" Mb.");}?>)</th>
<th class="hidden-phone hidden-tablet"><?=word_lang("daily limit")?></th>
<th class="hidden-phone hidden-tablet"><?=word_lang("Invoice")?></th>

<th><?=word_lang("status")?></th>
<th></th>

	
</tr>
<?
$tr=1;
while(!$rs->eof)
{

$cl3="";
$cl_script="";
if(isset($_SESSION["user_subscription_id"]) and !isset($_SESSION["admin_rows_subscription".$rs->row["id_parent"]]) and $rs->row["id_parent"]>$_SESSION["user_subscription_id"] and $_SESSION["user_subscription_id"]>0)
{
	$cl3="success";	
	$cl_script="onMouseover=\"deselect_row('subscription".$rs->row["id_parent"]."')\"";
}
?>
<tr id="subscription<?=$rs->row["id_parent"]?>" class='<?if($tr%2==0){echo("snd");}?> <?=$cl3?>' valign="top" <?=$cl_script?>>
<td><input type="checkbox" name="sel<?=$rs->row["id_parent"]?>" id="sel<?=$rs->row["id_parent"]?>"></td>
<td><?=$rs->row["id_parent"]?></td>




<td class="big"><?if($rs->row["payments"]>1){echo("<font color='red' class='big'>".$rs->row["payments"]."&nbsp;x&nbsp;</font>");}?><?=$rs->row["title"]?></td>
<td>

<div class="link_user"><a href="../customers/content.php?id=<?=user_url($rs->row["user"])?>"><?=$rs->row["user"]?></a></div>


</td>

<td class="gray hidden-phone hidden-tablet"><?=date(date_short,$rs->row["data1"])?></td>
<td class="gray hidden-phone hidden-tablet"><?=date(date_short,$rs->row["data2"])?></td>
<td class="hidden-phone hidden-tablet"><a href="../downloads/?search=<?=$rs->row["id_parent"]?>&search_type=subscription">
<?
$bandwidth=$rs->row["bandwidth"];
$bandwidth_text="";
if($subscription_limit=="Bandwidth")
{
	$bandwidth=float_opt($rs->row["bandwidth"],3);
	$bandwidth_text="Mb.";
}
if($subscription_limit=="Credits")
{
	$bandwidth=float_opt($rs->row["bandwidth"],2);
}
echo($bandwidth);
?>
(<?=$rs->row["bandwidth_limit"]?>) <?=$bandwidth_text?>
</a></td>

<td class="hidden-phone hidden-tablet">
<?
	if($rs->row["bandwidth_daily_limit"]!=0)
	{
		?><?=$rs->row["bandwidth_daily"]?> (<?=$rs->row["bandwidth_daily_limit"]?>) <?=$bandwidth_text?><?
	}
	else
	{
		echo(word_lang("no"));
	}
?>
</td>
<td>
<?

if($rs->row["total"]>0)
{
	$invoice_number = "";
	$link_class = "";
	
	$sql="select invoice_number,refund from invoices where order_type='subscription' and order_id=".$rs->row["id_parent"]." order by id";
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
<td>
<?


$cl="success";
if($rs->row["approved"]!=1)
{
$cl="danger";
}




?>
<div id="status<?=$rs->row["id_parent"]?>" name="status<?=$rs->row["id_parent"]?>"><a href="javascript:doLoad(<?=$rs->row["id_parent"]?>);"><span class="label label-<?=$cl?>"><?if($rs->row["approved"]==1){echo(word_lang("approved"));}else{echo(word_lang("pending"));}?></span></a></div>







</td>



<td><div class="link_edit"><a href="edit.php?id=<?=$rs->row["id_parent"]?>"><?=word_lang("edit")?></a></div></td>

</tr>
<?
$tr++;
$n++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>


<input type="submit" class="btn btn-danger" value="<?=word_lang("delete")?>"  style="margin:15px 0px 0px 6px">






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