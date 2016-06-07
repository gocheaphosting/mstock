<?
//Check access
admin_panel_access("settings_rightsmanaged");

if(!defined("site_root")){exit();}

?>

<br>

<a class="btn btn-success toright" href="price_content.php" style="margin-left:20px"><i class="icon-file icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add")?></a>

<p>
<a href="http://en.wikipedia.org/wiki/Rights_Managed">Rights Managed</a>, or RM, in photography and the stock photo industry, refers to a copyright license which, if purchased by a user, allows the one time use of the photo as specified by the license. If the user wants to use the photo for other uses an additional license needs to be purchased. RM licences can be given on a non-exclusive or exclusive basis. 
</p>


<br>

<?
$sql="select id,title,price from rights_managed order by id";
$rs->open($sql);
if(!$rs->eof)
{
	$tr=1;
	?>
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th style="width:60%"><b><?=word_lang("title")?>:</b></th>
	<th><b><?=word_lang("price")?>:</b></th>
	<th></th>
	<th></th>
	<th></th>
	</tr>
	<?
	while(!$rs->eof)
	{
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?>>
		<td class="big"><?=$rs->row["title"]?></td>
		<td class="big"><?=float_opt($rs->row["price"],2)?></td>
		<td><div class="link_edit"><a href='price_content.php?id=<?=$rs->row["id"]?>'><?=word_lang("edit")?></a></td>
		<td><div class="link_edit"><a href='content.php?id=<?=$rs->row["id"]?>'><?=word_lang("price scheme")?></a></td>
		<td>
		<div class="link_delete"><a href='price_delete.php?id=<?=$rs->row["id"]?>' onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div>
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

