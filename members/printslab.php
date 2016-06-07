<?$site="printslab";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>



<input type="button" value="<?=word_lang("add new gallery")?>" class="profile_button" onClick="location.href='printslab_content.php'">

<h1><?=word_lang("upload your photos and order prints")?></h1>



<?
$sql="select * from galleries where user_id='".(int)$_SESSION["people_id"]."' order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<table border="0" cellpadding="0" cellspacing="0" class="profile_table" width="100%" style="margin-top:15px">
	<tr>
	<th><?=word_lang("title")?></th>
	<th><?=word_lang("date")?></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	</tr>
	<?
	$tr=1;
	while(!$rs->eof)
	{
		?>
		<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
		<td><b><?=$rs->row["title"]?></b><br><small>[&nbsp;<?
				$sql="select count(id) as count_id from galleries_photos where id_parent=".$rs->row["id"];
				$ds->open($sql);
				echo($ds->row["count_id"]."&nbsp;".word_lang("photos"));
			?>&nbsp;]</small>
		</td>

		<td><div class="link_date"><?=date(date_format,$rs->row["data"])?></div></td>
		<td><input type="button" onClick="location.href='printslab_upload.php?id=<?=$rs->row["id"]?>'" class="isubmit" value="<?=word_lang("upload")?> <?=word_lang("photos")?>"></td>
		<td><input type="button" onClick="location.href='printslab_order.php?id=<?=$rs->row["id"]?>'" class="isubmit_orange" value="<?=word_lang("order prints")?>"></td>
		<td><div class="link_edit"><a href="printslab_content.php?id=<?=$rs->row["id"]?>"><?=word_lang("edit")?></a></div></td>
		
		<td><div class="link_delete"><a href="printslab_delete.php?id=<?=$rs->row["id"]?>" onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div></td>
		</tr>
		<?
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
<?
}
else
{
?>
	<b><?=word_lang("not found")?></b>
<?
}
?>








<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>