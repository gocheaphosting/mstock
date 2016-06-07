<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_support");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>



<h1><?=word_lang("support")?>:</h1>



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


<script type="text/javascript" src="<?=site_root?>/inc/js/raty/jquery.raty.min.js"></script>

<script type="text/javascript">
    $(function() {
      $.fn.raty.defaults.path = '<?=site_root?>/inc/js/raty/img';

      $('.star').raty({ score: 3 });
      
    });
</script>



<?
//Get Search
$search="";
if(isset($_GET["search"])){$search=result($_GET["search"]);}
if(isset($_POST["search"])){$search=result($_POST["search"]);}









//Items
$items=30;
if(isset($_GET["items"])){$items=(int)$_GET["items"];}
if(isset($_POST["items"])){$items=(int)$_POST["items"];}


//Search variable
$var_search="search=".$search."&items=".$items;






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
	
	$com2.=" and user_id='".user_url($search)."' ";
	
}





//Item's quantity
$kolvo=$items;


//Pages quantity
$kolvo2=k_str2;


//Page number
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}


$n=0;

$sql="select id,id_parent,admin_id,user_id,subject,message,data,viewed_admin,viewed_user,rating,closed from support_tickets where id_parent=0 ";


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
<span><?=word_lang("user")?>:</span>
<input type="text" name="search" style="width:200px" class="ft" value="<?=$search?>" onClick="this.value=''">
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





<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
<tr>
<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&aid=<?if($aid==2){echo(1);}else{echo(2);}?>">ID</a> <?if($aid==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($aid==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>
</th>
<th width="35%"><?=word_lang("subject")?></th>
<th class="hidden-phone hidden-tablet"><?=word_lang("rating")?></th>
<th class="hidden-phone hidden-tablet">
<a href="index.php?<?=$var_search?>&adate=<?if($adate==2){echo(1);}else{echo(2);}?>"><?=word_lang("date")?></a> <?if($adate==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($adate==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>
</th>
<th class="hidden-phone hidden-tablet"><?=word_lang("user")?></th>
<th class="hidden-phone hidden-tablet"><?=word_lang("status")?></th>



<th></th>

</tr>
<?
$tr=1;

while(!$rs->eof)
{
	$new_messages=0;
	$total_messages=1;
	$rating=0;
	$rating_count=0;
	
	if($rs->row["viewed_admin"]==0)
	{
		$new_messages++;
	}
	
	$sql="select id,viewed_admin,admin_id,rating,user_id from support_tickets where id_parent=".$rs->row["id"];
	$ds->open($sql);
	while(!$ds->eof)
	{
		if($ds->row["viewed_admin"]==0 and $ds->row["admin_id"]==0)
		{
			$new_messages++;
		}
		
		if($ds->row["admin_id"]!=0 and $ds->row["rating"]!=0)
		{
			$rating+=$ds->row["rating"];
			$rating_count++;
		}
		
		$total_messages++;
		$ds->movenext();
	}
	
	if($new_messages>0)
	{
		$style="badge-important";
	}
	else
	{
		$style="";
	}
	
?>
<tr valign="top" 
<?
if($new_messages>0)
{
	echo("class='snd2 danger'");
}
else
{
	if($tr%2==0)
	{
		echo("class='snd'");
	}
}
?>
>
<td class="hidden-phone hidden-tablet"><?=$rs->row["id"]?></td>
<td><span class="badge <?=$style?>"><?=$total_messages?></span> <a href="content.php?id=<?=$rs->row["id"]?>"><?=$rs->row["subject"]?></a></td>
<td class="hidden-phone hidden-tablet">
<?
if($rating_count!=0)
{
	$rating=$rating/$rating_count;
}
?>
<script type="text/javascript">
    			$(function() {
      				$('#star<?=$rs->row["id"]?>').raty({
      				score: <?=$rating?>,
 					half: true,
  					number: 5,
					readOnly   : true
				});
    			});
				</script>
				<div id="star<?=$rs->row["id"]?>" style="margin-top:7px"></div>
</td>
<td class="gray hidden-phone hidden-tablet"><?=show_time_ago($rs->row["data"])?></td>
<td class="hidden-phone hidden-tablet"><div class="link_user"><a href="../customers/content.php?id=<?=$rs->row["user_id"]?>"><?=user_url_back($rs->row["user_id"])?></a></div></td>
<td class="hidden-phone hidden-tablet">

<?
$cl="";
if($rs->row["closed"]!=1)
{
	$cl="class='red'";
}
?>

<div id="status<?=$rs->row["id"]?>" name="status<?=$rs->row["id"]?>" class="link_status"><a href="javascript:doLoad(<?=$rs->row["id"]?>);" <?=$cl?>><?if($rs->row["closed"]==1){echo(word_lang("closed"));}else{echo(word_lang("in progress"));}?></a></div>

					
</td>



<td><div class="link_delete"><a href="delete.php?id=<?=$rs->row["id"]?>&<?=$var_search.$var_sort?>"  onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div></td>
</tr>
<?
$n++;
$tr++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>



<div style="padding:25px 0px 0px 6px;"><?echo(paging($record_count,$str,$kolvo,$kolvo2,"index.php","&".$var_search.$var_sort));?></div>
<?
}
else
{
echo("<p><b>".word_lang("not found")."</b></p>");
}
?>

<? include("../inc/end.php");?>