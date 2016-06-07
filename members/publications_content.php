<?if(!defined("site_root")){exit();}?>


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


<form method="post" action="publications_edit.php" style="margin:0px"  id="sellerform" name="sellerform">
<?

//limit
$lm=" limit ".($kolvo*($str-1)).",".$kolvo;

$sql="select id_parent,title,description,published,userid,viewed,downloaded,data,server1,refuse_reason,url,exclusive from ".$table." where  (userid=".(int)$_SESSION["people_id"]." or author='".result($_SESSION["people_login"])."') ".$com." ".$com2;

$rs->open($sql);
$record_count=$rs->rc;

$sql2=$sql.$lm;

$rs->open($sql2);


if(!$rs->eof)
{
?>
<table border="0" cellpadding="0" cellspacing="0" class="profile_table">
<tr>
<th><input type="checkbox"  name="selector" value="1" onClick="publications_select_all(document.sellerform);"></th>
<th><?=word_lang("preview")?></th>

<th nowrap class='hidden-phone hidden-tablet'><a href="publications.php?d=<?=$d?>&status=<?=$pstatus?><?=$varsort_id?>">ID</a> <?if($pid==1){?><img src="<?=site_root?>/<?=$site_template_url?>/images/sort_up.gif"><?}?><?if($pid==2){?><img src="<?=site_root?>/<?=$site_template_url?>/images/sort_down.gif"><?}?></th>

<th nowrap class='hidden-phone hidden-tablet'><a href="publications.php?d=<?=$d?>&status=<?=$pstatus?><?=$varsort_title?>"><?=word_lang("title")?></a>  <?if($ptitle==1){?><img src="<?=site_root?>/<?=$site_template_url?>/images/sort_up.gif"><?}?><?if($ptitle==2){?><img src="<?=site_root?>/<?=$site_template_url?>/images/sort_down.gif"><?}?></th>

<th nowrap class='hidden-phone hidden-tablet'><a href="publications.php?d=<?=$d?>&status=<?=$pstatus?><?=$varsort_viewed?>"><?=word_lang("viewed")?></a> <?if($pviewed==1){?><img src="<?=site_root?>/<?=$site_template_url?>/images/sort_up.gif"><?}?><?if($pviewed==2){?><img src="<?=site_root?>/<?=$site_template_url?>/images/sort_down.gif"><?}?></th>

<th nowrap class='hidden-phone hidden-tablet'><a href="publications.php?d=<?=$d?>&status=<?=$pstatus?><?=$varsort_downloads?>"><?=word_lang("downloads")?></a>  <?if($pdownloads==1){?><img src="<?=site_root?>/<?=$site_template_url?>/images/sort_up.gif"><?}?><?if($pdownloads==2){?><img src="<?=site_root?>/<?=$site_template_url?>/images/sort_down.gif"><?}?></th>

<th nowrap class='hidden-phone hidden-tablet'><a href="publications.php?d=<?=$d?>&status=<?=$pstatus?><?=$varsort_data?>"><?=word_lang("date")?></a>  <?if($pdata==1){?><img src="<?=site_root?>/<?=$site_template_url?>/images/sort_up.gif"><?}?><?if($pdata==2){?><img src="<?=site_root?>/<?=$site_template_url?>/images/sort_down.gif"><?}?></th>

<th><?=word_lang("status")?></th>
<th></th>
</tr>
<?
$n=0;
$tr=1;
while(!$rs->eof)
{



$url=item_url($rs->row["id_parent"],$rs->row["url"]);

$generated="";

if($table=="photos")
{
	$delete_url="photo";
	$preview=show_preview($rs->row["id_parent"],"photo",1,1,$rs->row["server1"],$rs->row["id_parent"]);
}

if($table=="videos")
{
	if($global_settings["ffmpeg_cron"])
	{
		$sql="select data1 from ffmpeg_cron where id=".$rs->row["id_parent"]." and data2=0";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$generated=word_lang("Previews are being created. Queue number is");
			
			$queue=1;
			$sql="select count(id) as queue_count from ffmpeg_cron where data1<".$ds->row["data1"]." and data2=0";
			$dr->open($sql);
			if(!$dr->eof)
			{
				$queue=$dr->row["queue_count"];
			}

			$generated.=" <b>".$queue."</b>";
		}
	}	
	
	$delete_url="video";
	$preview=show_preview($rs->row["id_parent"],"video",1,1,$rs->row["server1"],$rs->row["id_parent"]);
}

if($table=="audio")
{
	$delete_url="audio";
	$preview=show_preview($rs->row["id_parent"],"audio",1,1,$rs->row["server1"],$rs->row["id_parent"]);
}

if($table=="vector")
{
	$delete_url="vector";
	$preview=show_preview($rs->row["id_parent"],"vector",1,1,$rs->row["server1"],$rs->row["id_parent"]);
}


?>
<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
<td>
<input type='checkbox' id='sel<?=$rs->row["id_parent"]?>' name='sel<?=$rs->row["id_parent"]?>'>
</td>
<td><?if($preview!=""){?><?if($rs->row["published"]==1){?><a href="<?=$url?>"><?}?><img src="<?=$preview?>" border="0"></a><?}?></td>
<td class='hidden-phone hidden-tablet'><?=$rs->row["id_parent"]?></td>
<td class='hidden-phone hidden-tablet'><?if($rs->row["published"]==1){?><a href="<?=$url?>"><?}?><b><?=$rs->row["title"]?></b></a><br><small><?=$generated?></small></td>
<td class='hidden-phone hidden-tablet'><?=$rs->row["viewed"]?></td>
<td class='hidden-phone hidden-tablet'><?=$rs->row["downloaded"]?></td>
<td class='hidden-phone hidden-tablet'><div class="link_date"><?=date(date_short,$rs->row["data"])?></div></td>
<td>
<?
if($rs->row["published"]==1){echo("<div class='link_approved'>".word_lang("approved")."</div>");}
if($rs->row["published"]==0){echo("<div class='link_pending'>".word_lang("pending")."</div>");}
if($rs->row["published"]==-1 and $rs->row["exclusive"]!=1)
{
	echo("<div class='link_pending'>".word_lang("declined")."</div>");
	if($rs->row["refuse_reason"]!="")
	{
		echo("<p><b>".word_lang("reason for rejection").":</b> ".$rs->row["refuse_reason"]."</p>");
	}
}
if($rs->row["published"]==-1 and $rs->row["exclusive"]==1)
{
	echo("<div class='link_approved'>".word_lang("sold")."</div>");
}
?>
</td>

<td><div class="link_edit">
<a href='<?
if($d==2){echo("filemanager_photo.php");}
if($d==3){echo("filemanager_video.php");}
if($d==4){echo("filemanager_audio.php");}
if($d==5){echo("filemanager_vector.php");}
?>?id=<?=$rs->row["id_parent"]?>&d=<?=$d?>&s=1'><?=word_lang("edit")?></div></a>
</td>
</tr>
<?
$n++;
$tr++;
$rs->movenext();
}
?>
</table>
<p>
<?
echo(paging($record_count,$str,$kolvo,$kolvo2,"publications.php","&d=".$d."&status=".$pstatus.$varsort));
?>
</p>
<p>
<input type="hidden" name="formaction" id="formaction" value="edit">
<input type="hidden" name="return_url" value="publications.php?str=1<?="&d=".$d."&status=".$pstatus.$varsort?>">

<input type="submit" class="isubmit" value="<?=word_lang("change")?>" style="margin-top:30px" onClick="document.getElementById('formaction').value='edit'"><input type="submit" class="isubmit_orange" value="<?=word_lang("delete")?>*" style="margin-top:30px;margin-left:30px" onClick="document.getElementById('formaction').value='delete';return confirm('<?=word_lang("delete")?>?');">
</p>
</form>
<p>* - <?=word_lang("it is impossible to remove approved files.")?></p>
<?
}
else
{
echo("<b>".word_lang("not found")."</b>");
}
?>

