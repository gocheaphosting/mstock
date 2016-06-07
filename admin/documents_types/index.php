<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_documents");
?>
<? include("../inc/begin.php");?>







<a class="btn btn-success toright" href="add.php"><i class="icon-user icon-white fa fa-plus"></i> <?=word_lang("add")?></a>



<h1><?=word_lang("Documents types")?>:</h1>

<p>Sometimes the users have to upload different documents to identify a person, a country and etc.</p>


<?
$sql="select * from documents_types order by priority";
$rs->open($sql);
if(!$rs->eof)
{
	$tr=1;
	?>
	<form method="post" action="change.php">
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("enabled")?>:</b></th>
	<th><b><?=word_lang("priority")?>:</b></th>
	<th><b><?=word_lang("title")?>:</b></th>
	<th><b><?=word_lang("description")?>:</b></th>
	<th><b><?=word_lang("size")?> (MB):</b></th>
	<th><b><?=word_lang("buyer")?>:</b></th>
	<th><b><?=word_lang("seller")?>:</b></th>
	<th><b><?=word_lang("affiliate")?>:</b></th>
	<th></ht>
	</tr>
	<?
	while(!$rs->eof)
	{
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
		<td><input name="enabled<?=$rs->row["id"]?>" type="checkbox" value="1" <?if($rs->row["enabled"]==1){echo("checked");}?>></td>
		<td><input name="priority<?=$rs->row["id"]?>" type="text" style="width:40px" value="<?=$rs->row["priority"]?>"></td>
		<td><input name="title<?=$rs->row["id"]?>" type="text" style="width:220px" value="<?=$rs->row["title"]?>"></td>
		<td><input name="description<?=$rs->row["id"]?>" type="text" style="width:300px" value="<?=$rs->row["description"]?>"></td>
		<td><input name="filesize<?=$rs->row["id"]?>" type="text" style="width:50px" value="<?=$rs->row["filesize"]?>"></td>
		<td><input name="buyer<?=$rs->row["id"]?>" type="checkbox" value="1" <?if($rs->row["buyer"]==1){echo("checked");}?>></td>
		<td><input name="seller<?=$rs->row["id"]?>" type="checkbox" value="1" <?if($rs->row["seller"]==1){echo("checked");}?>></td>
		<td><input name="affiliate<?=$rs->row["id"]?>" type="checkbox" value="1" <?if($rs->row["affiliate"]==1){echo("checked");}?>></td>
		<td>
		<div class="link_delete"><a href='delete.php?id=<?=$rs->row["id"]?>'><?=word_lang("delete")?></a></div>
		</td>
		</tr>
		<?
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
	<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin:10px 0px 0px 6px">
	</form><br>
<?
}
?>





<? include("../inc/end.php");?>