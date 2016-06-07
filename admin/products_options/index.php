<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_productsoptions");
?>
<? include("../inc/begin.php");?>

<a class="btn btn-success toright" href="content.php"><i class="icon-plane icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add")?></a>


<h1><?=word_lang("products options")?></h1>

<?
$sql="select * from products_options order by title";
$rs->open($sql);
if(!$rs->eof)
{
	$tr=1;
	?>
	<form method="post" action="change.php">
	
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("enabled")?>:</b></th>
	<th style="width:70%"><b><?=word_lang("title")?>:</b></th>
	<th></th>
	<th></th>
	</tr>
	<?
	while(!$rs->eof)
	{	
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
		<td><input type="checkbox" name="activ<?=$rs->row["id"]?>" <?if($rs->row["activ"]==1){echo("checked");}?>></td>
		<td><span class="big"><?=$rs->row["title"]?></span></td>
		<td><div class="link_edit"><a href='content.php?id=<?=$rs->row["id"]?>'><?=word_lang("edit")?></a></td>
		<td><div class="link_delete"><a href='delete.php?id=<?=$rs->row["id"]?>'><?=word_lang("delete")?></a></div></td>
		</tr>
		<?
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
	
	
	
<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>" style="margin:15px 0px 0px 6px">





</form>
<?
}
else
{
 	echo("<p><b>".word_lang("not found")."</b></p>");
}
?>



<? include("../inc/end.php");?>