<?
//Check access
admin_panel_access("settings_payout");

?>
<?if(!defined("site_root")){exit();}?>

<form method="post" action="banks_add.php" style="margin:5px 0px 15px 6px">
<input name="new" type="text" value="" style="width:200px;display:inline">&nbsp;<input type="submit" value="<?=word_lang("add")?>" class="btn btn-success" style="display:inline">
</form>


<form method="post" action="banks_change.php">
<?
$tr=1;
$sql="select * from banks order by title";
$rs->open($sql);
if(!$rs->eof)
{
?>
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover">
<tr>
<th><b><?=word_lang("name")?></b></th>
<th><b><?=word_lang("delete")?></b></th>
</tr>

<?

while(!$rs->eof)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td><input type="text" name="title<?=$rs->row["id"]?>" value="<?=$rs->row["title"]?>" style="width:250px"></td>
<td><input type="checkbox" name="delete<?=$rs->row["id"]?>"></td>
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
			<input type="submit" value="<?=word_lang("change")?>" class="btn btn-primary" style="margin-top:20px">
		</div>
	</div>
	
<?
}
else
{
	echo("<p><b>".word_lang("not found")."</b></p>");
}
?>

</form>
