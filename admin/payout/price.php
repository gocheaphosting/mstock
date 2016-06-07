<?
//Check access
admin_panel_access("settings_payout");

?>
<?if(!defined("site_root")){exit();}?>


<form method="post" action="change2.php">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover">

<tr>
<th><?=word_lang("credits")?></th>
<th><?=word_lang("price")?>:</th>
</tr>
<tr>
<td class="big">1 Credit</td>
<td><input type="text" name="price" value="<?=float_opt($global_settings["payout_price"],2)?>" style="width:70px"></td>
</tr>
</table>
</div></div></div></div></div></div></div></div>
<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin:10px 0px 0px 6px">
</form>
