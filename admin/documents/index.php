<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_documents");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>




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

function fstatus(fid,fdo) {
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
document.getElementById('status'+fid).innerHTML =req.responseText;

        }
    }
    req.open(null, 'status.php', true);
    req.send( {'fid':fid,'fdo':fdo} );
}
</script>





<h1><?=word_lang("Documents")?>:</h1>







<?
//Get Search
$search="";
if(isset($_GET["search"])){$search=result($_GET["search"]);}
if(isset($_POST["search"])){$search=result($_POST["search"]);}

//Get Search type
$search_type="";
if(isset($_GET["search_type"])){$search_type=result($_GET["search_type"]);}
if(isset($_POST["search_type"])){$search_type=result($_POST["search_type"]);}

//Get Status
$status=2;
if(isset($_REQUEST["status"])){$status=(int)$_REQUEST["status"];}





//Items
$items=30;
if(isset($_REQUEST["items"])){$items=(int)$_REQUEST["items"];}



//Search variable
$var_search="search=".$search."&search_type=".$search_type."&items=".$items."&status=".$status;






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
	$com2.=" and user_id=".user_url($search)." ";
}

if($search_type != 0)
{
	$com2.=" and id_parent=".(int)$search_type." ";
}

if($status != 2)
{
	$com2.=" and status=".(int)$status." ";
}





//Item's quantity
$kolvo=$items;


//Pages quantity
$kolvo2=k_str2;


//Page number
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}


$n=0;

$sql="select id,title,status,comment,filename,data,user_id from documents where id>0 ";


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
	<span><?=word_lang("login")?>:</span>
	<input type="text" name="search" style="width:100px;display:inline" class="ft" value="<?=$search?>" onClick="this.value=''">
</div>

<div class="toleft">
	<span><?=word_lang("Documents types")?>:</span>
	<select name="search_type" style="width:200px;display:inline" class="ft">
	<option value="0"><?=word_lang("all")?></option>
	<?
	$sql="select id,title from documents_types where enabled=1 order by priority";
	$ds->open($sql);
	while(!$ds->eof)
	{
		$sel="";
		if($search_type==$ds->row["id"])
		{
			$sel="selected";
		}
		?>
		<option value="<?=$ds->row["id"]?>" <?=$sel?>><?=$ds->row["title"]?></option>
		<?
		$ds->movenext();
	}
	?>
	</select>
</div>

<div class="toleft">
	<span><?=word_lang("status")?>:</span>
	<select name="status" style="width:150px;display:inline" class="ft">
	<option value="2"><?=word_lang("all")?></option>
	<option value="1" <?if($status==1){echo("selected");}?>><?=word_lang("approved")?></option>
	<option value="0" <?if($status==0){echo("selected");}?>><?=word_lang("pending")?></option>
	<option value="-1" <?if($status==-1){echo("selected");}?>><?=word_lang("declined")?></option>

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
<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
<tr>
<th><input type="checkbox"  name="selector" value="1" onClick="publications_select_all(document.adminform);"></th>
<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&aid=<?if($aid==2){echo(1);}else{echo(2);}?>">ID</a> <?if($aid==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($aid==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>
</th>
<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&adate=<?if($adate==2){echo(1);}else{echo(2);}?>"><?=word_lang("date")?></a> <?if($adate==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($adate==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>
</th>
<th><?=word_lang("user")?></th>
<th><?=word_lang("Documents")?></th>
<th class='hidden-phone hidden-tablet'><?=word_lang("file")?></th>
<th class='hidden-phone hidden-tablet'><?=word_lang("size")?></th>
<th><?=word_lang("status")?></th>




</tr>
<?
$tr=1;
while(!$rs->eof)
{
	$cl3="";
	$cl_script="";
	if(isset($_SESSION["user_documents_id"]) and !isset($_SESSION["admin_rows_documents".$rs->row["id"]]) and $rs->row["id"]>$_SESSION["user_documents_id"])
	{
		$cl3="success";	
		$cl_script="onMouseover=\"deselect_row('documents".$rs->row["id"]."')\"";
	}
	
	$size=filesize($_SERVER["DOCUMENT_ROOT"].site_root."/content/users/doc_".$rs->row["id"]."_".$rs->row["filename"]);
?>
<tr id="documents<?=$rs->row["id"]?>" class='<?if($tr%2==0){echo("snd");}?> <?=$cl3?>' valign="top" <?=$cl_script?>>
<td><input type="checkbox" name="sel<?=$rs->row["id"]?>" id="sel<?=$rs->row["id"]?>"></td>
<td class="hidden-phone hidden-tablet"><?=$rs->row["id"]?></td>
<td class="gray hidden-phone hidden-tablet"><?=show_time_ago($rs->row["data"])?></td>
<td class="hidden-phone hidden-tablet"><div class="link_user">
<a href="../customers/content.php?id=<?=$rs->row["user_id"]?>"><?=user_url_back($rs->row["user_id"])?></a>
</div>
</td>
<td><?=$rs->row["title"]?></td>
<td class="hidden-phone hidden-tablet"><a href="<?=site_root."/content/users/doc_".$rs->row["id"]."_".$rs->row["filename"]?>" target="blank"><?=$rs->row["filename"]?></a></td>
<td class="hidden-phone hidden-tablet"><?=float_opt($size/(1024*1024),3)." Mb."?></td>
<td>
<div id="status<?=$rs->row["id"]?>">


<a href="javascript:fstatus(<?=$rs->row ["id"]?>,1);" <?if($rs->row["status"]!=1){?>class="gray"<?}?>><?=word_lang("approved")?></a><br>
<a href="javascript:fstatus(<?=$rs->row ["id"]?>,0);" <?if($rs->row["status"]!=0){?>class="gray"<?}?>><?=word_lang("pending")?></a><br>
<a href="javascript:fstatus(<?=$rs->row ["id"]?>,-1);" <?if($rs->row["status"]!=-1){?>class="gray"<?}?>><?=word_lang("declined")?></a>



</div>

</td>
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