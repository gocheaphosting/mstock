<?if(!defined("site_root")){exit();}?>
<?include("payments_settings.php");?>




<form method="post" action="billing.php?type=credits">
<table border="0" cellpadding="0" cellspacing="0" class="profile_table" width="100%">
<tr>
<th width="50"></th>
<th><b><?=word_lang("credits")?></b></th>
<th><b><?=word_lang("price")?></b></th>
</tr>
<?
$i=0;
$tr=1;
$sql="select * from credits order by priority";
$rs->open($sql);
while(!$rs->eof)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td align="center"><input name="credits" type="radio" value="<?=$rs->row["id_parent"]?>" <?if($i==0){echo("checked");}?>></td>
<td><?=$rs->row["quantity"]?></td>
<td>
<span class="price"><?=currency(1,false)?><?=float_opt($rs->row["price"],2)?> <?=currency(2,false)?></span>&nbsp;&nbsp;&nbsp;<span class="smalltext">(<?=currency(1,false)?><?=float_opt($rs->row["price"]/$rs->row["quantity"],2)?><?=currency(2,false)?>/credit)</span>
</td>
</tr>
<?
$i++;
$tr++;
$rs->movenext();
}
?>

</table>
<input type="hidden" name="tip" value="credits">
<input class='isubmit' type="submit" value="<?=word_lang("buy")?>" style="margin-top:10px">

</form>