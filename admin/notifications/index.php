<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_notifications");
?>
<? include("../inc/begin.php");?>




<h1><?=word_lang("notifications")?></h1>

<ul>

<li>The notification's emails can have 2 formats: <b>text</b> and <b>html</b>.</li> 

<li>You can find the templates for the html notifications here on ftp: <b>/templates/emails/</b>. There are <b>header.tpl</b>, <b>footer.tpl</b>, <b>style.css</b></li>

</ul>

<script>
function open_preview(value)
{
	$.colorbox({width:"700",height:"500", href:"preview.php?events="+value});
}
</script>


<form method="post" action="change.php">
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover" style="width:100%">
<tr>

<th><b><?=word_lang("enabled")?></b></th>
<th style="width:70%"><b><?=word_lang("title")?></b></th>
<th></th>
<th></th>
</tr>
<?
$tr=1;
$sql="select * from notifications order by priority";
$rs->open($sql);
while(!$rs->eof)
{
?>
<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
<td><input type="checkbox" name="e<?=$rs->row["events"]?>" <?if($rs->row["enabled"]==1){echo("checked");}?>></td>
<td class="big"><?=$rs->row["title"]?></td>
<td><div class="link_preview"><a href="javascript:open_preview('<?=$rs->row["events"]?>')" class="preview_link"><?=word_lang("preview")?></a></div></td>
<td><div class="link_edit"><a href="content.php?events=<?=$rs->row["events"]?>"><?=word_lang("edit")?></a></div></td>
</tr>
<?
$tr++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>



	<div id="button_bottom_static">
		<div id="button_bottom_layout"></div>
		<div id="button_bottom">
			<input type="submit" value="<?=word_lang("change")?>" class="btn btn-primary">
		</div>
	</div>
</form>









<? include("../inc/end.php");?>