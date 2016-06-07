<?
//Check access
admin_panel_access("settings_rightsmanaged");

if(!defined("site_root")){exit();}

?>

<br>

<a class="btn btn-success toright" href="groups_content.php" style="margin-left:20px"><i class="icon-th icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add group")?></a>



<br><br><br>

<?
$sql="select id,title from rights_managed_groups order by id";
$rs->open($sql);
if(!$rs->eof)
{
	$tr=1;
	?>
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th style="width:70%"><b><?=word_lang("title")?>:</b></th>
	<th><b><?=word_lang("edit")?>:</b></th>
	<th><b><?=word_lang("delete")?></b></th>
	</tr>
	<?
	while(!$rs->eof)
	{
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?>>
		<td><span class="big"><?=$rs->row["title"]?></span></td>
		<td><div class="link_edit"><a href='groups_content.php?id=<?=$rs->row["id"]?>'><?=word_lang("edit")?></a></td>
		<td>
		<div class="link_delete"><a href='groups_delete.php?id=<?=$rs->row["id"]?>' onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div>
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

