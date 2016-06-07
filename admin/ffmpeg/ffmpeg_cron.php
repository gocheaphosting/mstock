<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_ffmpeg");
$section="ffmpeg_cron";
?>
<? include("../inc/begin.php");?>



<? include("menu.php");?>



<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">

<p>FFMPEG's preview's generation takes a few seconds or minutes if the video files are large. Sometimes it makes sense to move the generation to a separate process so that the users don't wait until the server creates the previews. </p>


<p>How it works:</p>
<ul>
<li>A seller uploads a video. The previews are not created.</li> 
<li>The video is placed in the queue. The publication isn't active.</li>
<li>The server's cron script generates *.jpg and *.mp4 previews file by file. When the previews are ready the video is published on the site.</li>
</ul>


<p>
You can find the cron script here:<br>
<b><?=site_root?>/members/cron_ffmpeg.php</b>
</p>

<p>
You can <b>rename</b> the scripts for <b>security reasons</b> on ftp.
</p>

<p>The cron command's syntax depends on the server's settings.
We advice to use the commands which ping cron's URL - not physical path to the cron php file. </br>
</p>

<p><b>Examples of the cron commands:</b></p>

<ul>
<li>/usr/bin/lynx -source <?=surl?><?=site_root?>/members/cron_ffmpeg.php</li>
<li>GET <?=surl?><?=site_root?>/members/cron_ffmpeg.php > /dev/null</li>
</ul>

<form method="post" action="enable_cron.php">
<div class="form_field">
<input type="checkbox" value="1" name="cron" <?if($global_settings["ffmpeg_cron"]){echo("checked");}?>> <b>Enable</b> FFMPEG queue.
</div>

<div class="form_field">
<input type="submit" value="<?=word_lang("save")?>" class="btn btn-primary">
</div>
</form>

</div>

<div class="subheader"><?=word_lang("Queue")?></div>
<div class="subheader_text">






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
	if($adate==1){$com=" order by data1 ";}
	if($adate==2){$com=" order by data1 desc ";}
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
}





//Item's quantity
$kolvo=$items;


//Pages quantity
$kolvo2=k_str2;


//Page number
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}


$n=0;

$sql="select id,data1,data2 from ffmpeg_cron where id>0 ";


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


<form method="get" action="ffmpeg_cron.php" style="margin:0px">

<div class="toleft">
<span><?=word_lang("search")?>:</span>
<input type="text" name="search" style="width:200px" class="ft" value="<?=$search?>" onClick="this.value=''"  value="Publication ID">
<input type="hidden" name="search_type" value="id">
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


<div style="padding-bottom:15px"><?echo(paging($record_count,$str,$kolvo,$kolvo2,"index.php","&d=5&".$var_search.$var_sort));?></div>

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



<form method="post" action="delete.php"  id="adminform" name="adminform">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover">
<tr>
<th><input type="checkbox"  name="selector" value="1" onClick="publications_select_all(document.adminform);"></th>
<th><?=word_lang("preview")?></th>
<th>
<a href="index.php?d=5&<?=$var_search?>&aid=<?if($aid==2){echo(1);}else{echo(2);}?>">ID</a> <?if($aid==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($aid==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>
</th>



<th>
<a href="index.php?d=5&<?=$var_search?>&adate=<?if($adate==2){echo(1);}else{echo(2);}?>"><?=word_lang("date")?></a> <?if($adate==1){?><img src="<?=site_root?>/admin/images/sort_up.gif" width="11" height="8"><?}?><?if($adate==2){?><img src="<?=site_root?>/admin/images/sort_down.gif" width="11" height="8"><?}?>

</th>
<th><?=word_lang("Creation date")?></th>
	
</tr>
<?
$tr=1;
while(!$rs->eof)
{



?>
<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
<td><input type="checkbox" name="delete<?=$rs->row["id"]?>" value="1"></td>
<td>
<?
$item_img=show_preview($rs->row["id"],"video",1,1,"",$rs->row["id"]);
?>
<img src="<?=$item_img?>" width="50">
</td>
<td>

<a href="../catalog/content.php?id=<?=$rs->row["id"]?>"><?=$rs->row["id"]?></a></td>


<td><?=date(datetime_format,$rs->row["data1"])?></td>
<td>
<?
if($rs->row["data2"]!=0)
{
	echo(date(datetime_format,$rs->row["data2"]));
}
else
{
	echo("&mdash;");
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
<input type="submit" value="<?=word_lang("delete")?>" style="margin:10px 0px 0px 6px" class="btn btn-danger">


</form>




<div style="padding-top:25px;"><?echo(paging($record_count,$str,$kolvo,$kolvo2,"ffmpeg_cron.php",$var_search.$var_sort));?></div>
<?
}
else
{
echo("<p><b>".word_lang("not found")."</b></p>");
}
?>






</div>



</div>












<? include("../inc/end.php");?>