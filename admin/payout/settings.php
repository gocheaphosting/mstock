<?
//Check access
admin_panel_access("settings_payout");

?>
<?if(!defined("site_root")){exit();}?>




<form method="post" action="settings_change.php">


	
	<div class='admin_field'>
	<span><?=word_lang("Balance threshold for payout")?> (<?=$currency_code1?>):</span>
	<input type="text" name="payout_limit" value="<?=float_opt($global_settings["payout_limit"],2)?>" class="form-control" style="width:100px"><br>
	</div>
	

	

	
	<div class='admin_field'>
	<span><?=word_lang("User may set balance threshold for payout")?>:</span>
	<input type="checkbox" name="payout_set" value="1" <?if($global_settings["payout_set"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("select action")?>:</span>
	<select name="payout_action" class="form-control" style="width:400px">
		<option value="0"><?=word_lang("Not to change current user's balance thresholds")?></option>
		<option value="1"><?=word_lang("Change current user's balance thresholds")?></option>
	</select>
	</div>
	

	


	
	<div class='admin_field'>
	<input  class="btn btn-primary" type="submit" value="<?=word_lang("save")?>">
	</div>

	</form>



<br><br>


<?
$tr=1;
$sql="select * from payout";
$rs->open($sql);
if(!$rs->eof)
{
?>
<form method="post" action="change.php">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover">
<tr>
<th style="width:80%"><b><?=word_lang("payment gateways")?>:</b></th>
<th><b><?=word_lang("enabled")?>:</b></th>
</tr>
<?
while(!$rs->eof)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td class="big"><?=$rs->row["title"]?></td>
<td><input name="activ_<?=$rs->row["svalue"]?>" type="checkbox" <?if($rs->row["activ"]==1){echo("checked");}?>></td>
</tr>
<?
$tr++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>
<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>" style="margin:10px 0px 0px 6px">
</form><br>
<?
}
?>
