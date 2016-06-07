<?if(!defined("site_root")){exit();}?>




<form method="get" action="credits.php">
<input type="hidden" name="d" value="1">
<table border="0" cellpadding="0" cellspacing="0" class="profile_table" width="100%">
<tr>
<th></th>
<th width="95%"><b><?=word_lang("select payment method")?></b></th>
</tr>
<tr>
<td align="center"><input name="type" type="radio" value="money" checked class="forms-control"></td>
<td><?=word_lang("credit card")?></td>
</tr>
<tr>
<td align="center"><input name="type" type="radio" value="mobile" class="forms-control"></td>
<td><?=word_lang("mobile payment")?></td>
</tr>
</table>
<input type="hidden" name="tip" value="credits">
<input class='isubmit' type="submit" value="<?=word_lang("buy")?>" style="margin-top:10px">

</form>