<?
//Check access
admin_panel_access("settings_audio");

if(!defined("site_root")){exit();}
?>

<?
$tr=1;
$sql="select * from audio_fields order by priority";
$rs->open($sql);
if(!$rs->eof)
{
?>
<form method="post" action="fields_change.php">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover">
<tr>

<th><b><?=word_lang("name")?>:</b></th>
<th><b><?=word_lang("enable")?>:</b></th>
<th><b><?=word_lang("required")?>:</b></th>

</tr>
<?
while(!$rs->eof)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<input type="hidden" name="priority<?=$rs->row["id"]?>" value="<?=$rs->row["priority"]?>">
<td class='big'><?=word_lang($rs->row["name"])?></td>
<td>
<?if($rs->row["always"]!=1){?>
<input type="checkbox" name="enable<?=$rs->row["id"]?>" value="1" <?if($rs->row["activ"]==1){echo("checked");}?>>
<?}else{?>
<input type="hidden" name="enable<?=$rs->row["id"]?>" value="1"><img src="../images/design/r18.gif" width="14" height="11">
<?}?>
</td>
<td><input type="checkbox" name="required<?=$rs->row["id"]?>" value="1" <?if($rs->row["required"]==1){echo("checked");}?>></td>
</tr>

<?
$tr++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>

<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>" style="margin:10px 0px 0px 6px"></form>

<?
}
?>