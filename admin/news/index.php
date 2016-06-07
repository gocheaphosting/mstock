<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("pages_news");
?>
<? include("../inc/begin.php");?>





<a class="btn btn-success toright" href="content.php"><i class="icon-comment icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add")?></a>






<h1><?=word_lang("News")?>:</h1>



<?
$sql="select id_parent,announce,content,data,title from news order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
	$tr=1;
	?>
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("title")?>:</b></th>
	<th><b><?=word_lang("description")?>:</b></th>
	<th><b><?=word_lang("date")?>:</b></th>
	<th></th>
	<th></th>
	</tr>
	<?
	while(!$rs->eof)
	{	
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
		<td class="big"><?=$rs->row["title"]?></td>
		<td><?=$rs->row["announce"]?></td>
		<td class="gray"><?=date(date_short,$rs->row["data"])?></td>
		<td><div class="link_edit"><a href='content.php?id=<?=$rs->row["id_parent"]?>'><?=word_lang("edit")?></a></td>
		<td>
		<div class="link_delete"><a href='delete.php?id=<?=$rs->row["id_parent"]?>'><?=word_lang("delete")?></a></div>
		</td>
		</tr>
		<?
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
<?
}
?>





<? include("../inc/end.php");?>