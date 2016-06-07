<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_invoices");

include($_SERVER["DOCUMENT_ROOT"].site_root."/members/payments_settings.php");
?>
<? include("../inc/begin.php");?>

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
</script>



<h1><?=word_lang("Invoices")?></h1>


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
	if($adate==1){$com=" order by id ";}
	if($adate==2){$com=" order by id desc ";}
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
	if($search_type=="invoice")
	{
		$com2.=" and invoice_number='".(int)$search."' ";
	}
	if($search_type=="id")
	{
		$com2.=" and order_id=".(int)$search." ";
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

$sql="select id,invoice_number,order_id,order_type,status,refund from invoices where id>0 ";


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
<option value="invoice" <?if($search_type=="invoice"){echo("selected");}?>><?=word_lang("Invoice number")?></option>
<option value="id" <?if($search_type=="id"){echo("selected");}?>><?=word_lang("order")?> ID</option>
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
<th><?=word_lang("Invoice number")?></th>



<th><?=word_lang("order")?></th>

<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&adate=<?if($adate==2){echo(1);}else{echo(2);}?>"><?=word_lang("date")?></a> <?if($adate==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($adate==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>
</th>

<th class="hidden-phone hidden-tablet"><?=word_lang("total")?></th>
<th class="hidden-phone hidden-tablet"><?=word_lang("user")?></th>
<th><?=word_lang("status")?></th>
<th></th>
<th></th>
<th></th>

</tr>
<?
$tr=1;
while(!$rs->eof)
{

$cl3="";
$cl_script="";
if(isset($_SESSION["user_invoices_id"]) and !isset($_SESSION["admin_rows_invoices".$rs->row["id"]]) and $rs->row["id"]>$_SESSION["user_invoices_id"] and $_SESSION["user_invoices_id"]>0)
{
	$cl3="success";	
	$cl_script="onMouseover=\"deselect_row('invoices".$rs->row["id"]."')\"";
}
?>
<tr id="invoices<?=$rs->row["id"]?>" class='<?if($tr%2==0){echo("snd");}?> <?=$cl3?>' valign="top" <?=$cl_script?>>
<td><input type="checkbox" name="sel<?=$rs->row["id"]?>" id="sel<?=$rs->row["id"]?>"></td>


<td>
<?
if($rs->row["refund"] == 1)
{
	$link_class=" class='red'";
	$word_refund = word_lang("Refund money").": ";
	$symbol_minus = "-";
}
else
{
	$link_class="";
	$word_refund = "";
	$symbol_minus = "";
}
?>

<a href="../invoices/invoice.php?id=<?=$rs->row["invoice_number"]?>" <?=$link_class?>><i class="fa fa-file-pdf-o"></i>
<?
if($rs->row["refund"] == 1)
{
	echo("#".$global_settings["credit_notes_prefix"].$rs->row["invoice_number"]);
}
else
{
	echo("#".$global_settings["invoice_prefix"].$rs->row["invoice_number"]);
}
?></a></td>




<td>
<?
$order_date = 0;
$order_total = 0;
$order_user = 0;


if($rs->row["order_type"]=="orders")
{
	$sql="select data,total,user from orders where id=".$rs->row["order_id"];
	$ds->open($sql);
	if(!$ds->eof)
	{
		$order_date = $ds->row["data"];
		$order_total = $ds->row["total"];	
		$order_user = $ds->row["user"];
	}	
	?>
		<div class="link_order"><a href="../orders/order_content.php?id=<?=$rs->row["order_id"]?>" <?=$link_class?>><?=$word_refund?><?=word_lang("order")?> #<?=$rs->row["order_id"]?></a></div>
	<?
}
if($rs->row["order_type"]=="credits")
{
	$sql="select data,total,user from credits_list where id_parent=".$rs->row["order_id"];
	$ds->open($sql);
	if(!$ds->eof)
	{
		$order_date = $ds->row["data"];
		$order_total = $ds->row["total"];
		$order_user = user_url($ds->row["user"]);
	}	
	?>
		<div class="link_order"><a href="../credits/index.php?search=<?=$rs->row["order_id"]?>&search_type=id" <?=$link_class?>><?=$word_refund?><?=word_lang("credits")?> #<?=$rs->row["order_id"]?></a></div>
	<?
}
if($rs->row["order_type"]=="subscription")
{
	$sql="select data1,total,user from subscription_list where id_parent=".$rs->row["order_id"];
	$ds->open($sql);
	if(!$ds->eof)
	{
		$order_date = $ds->row["data1"];
		$order_total = $ds->row["total"];	
		$order_user = user_url($ds->row["user"]);
	}	
	?>
		<div class="link_order"><a href="../subscription_list/edit.php?id=<?=$rs->row["order_id"]?>" <?=$link_class?>><?=$word_refund?><?=word_lang("subscription")?> #<?=$rs->row["order_id"]?></a></div>
	<?
}
?>
</td>

<td class="gray hidden-phone hidden-tablet"><?=date(datetime_format,$order_date)?></td>








<td class="hidden-phone hidden-tablet"><b><?=$symbol_minus?><?=currency(1,false);?><?=float_opt($order_total,2)?>&nbsp;<?=currency(2,false);?></b></td>
<td class="hidden-phone hidden-tablet">
<?
$sql="select id_parent,login,country,business,country_checked,country_checked_date ,vat_checked,vat_checked_date from users where id_parent=".(int)@$order_user;
$ds->open($sql);
if(!$ds->eof)
{
	echo('<div><a href="../customers/content.php?id=' . $ds->row["id_parent"] . '"><i class="fa fa-user"></i> ' . $ds->row["login"] . '</a></div>');
	
	if($ds->row["business"])
	{
		echo('<div><small>' . word_lang("business") . ' ');
		
		if((int)$ds->row["vat_checked"] == 0)
		{
			echo('<span class="label label-warning">' . word_lang("Not checked") . '</span><br>');
		}
		
		if((int)$ds->row["vat_checked"] == 1)
		{
			echo('<span class="label label-success">' . word_lang("Valid") . '</span><br>');
		}
		
		if((int)$ds->row["vat_checked"] == -1)
		{
			echo('<span class="label label-danger">' . word_lang("Invalid") . '</span><br>');
		}
		
		if((int)$ds->row["vat_checked_date"] != 0)
		{
			echo(word_lang("Last check") . ': ' . show_time_ago($ds->row["vat_checked_date"]));
		}
		
		echo('</small></div>');	
	}
	else
	{
		echo('<div><small>' . word_lang("individual") . '</small></div>');
	}
	
	echo('<div style="margin-top:10px"><small>' . $ds->row["country"] . ' ');
	
	if((int)$ds->row["country_checked"] == 0)
	{
		echo('<span class="label label-warning">' . word_lang("Not checked") . '</span><br>');
	}
	
	if((int)$ds->row["country_checked"] == 1)
	{
		echo('<span class="label label-success">' . word_lang("Valid") . '</span><br>');
	}
	
	if((int)$ds->row["country_checked"] == -1)
	{
		echo('<span class="label label-danger">' . word_lang("Invalid") . '</span><br>');
	}
	
	if((int)$ds->row["country_checked_date"] != 0)
	{
		echo(word_lang("Last check") . ': ' . show_time_ago($ds->row["country_checked_date"]));
	}
	
	echo('</small></div>');
}
?>
</td>
<td class="hidden-phone hidden-tablet">
<?
$cl="success";
if($rs->row["status"]!=1)
{
$cl="danger";
}
?>

<div id="status<?=$rs->row["id"]?>" name="status<?=$rs->row["id"]?>"><a href="javascript:doLoad(<?=$rs->row["id"]?>);"><span class="label label-<?=$cl?>"><?if($rs->row["status"]==1){echo(word_lang("published"));}else{echo(word_lang("pending"));}?></span></a></div>
</td>

<td><a href="../invoices/invoice.php?id=<?=$rs->row["invoice_number"]?>&change=1"><i class="fa fa-edit"></i>
<?=word_lang("edit")?></a></td>

<td><a href="../invoices/invoice_html.php?id=<?=$rs->row["invoice_number"]?>"><i class="fa fa-file-text"></i>
HTML</a></td>
<td><a href="../invoices/invoice_pdf.php?id=<?=$rs->row["invoice_number"]?>"><i class="fa fa-file-pdf-o"></i>
PDF</a></td>
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