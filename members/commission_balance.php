<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>

<h1><?=word_lang("my commission")?> &mdash; <?=word_lang("balance")?></h1>




<table border=0 cellpadding=0 cellspacing=0 class=profile_table width="100%">
<tr>
<th><b><?=word_lang("balance")?></b></th>
<th><b><?=word_lang("earning")?></b></th>
<th><b><?=word_lang("refund")?></b></th>
</tr>

<tr>
<td>

<?
$total=0;
$sql="select user,total from commission where user=".(int)$_SESSION["people_id"]." and status=1";
$ds->open($sql);
while(!$ds->eof)
{
$total+=$ds->row["total"];
$ds->movenext();
}
?>
<span class="price"><b><?=currency(1,false);?><?=float_opt($total,2)?> <?=currency(2,false);?></b></span>


</td>
<td>
<?
$total=0;
$sql="select user,total from commission where total>0 and user=".(int)$_SESSION["people_id"]." and status=1";
$ds->open($sql);
while(!$ds->eof)
{
$total+=$ds->row["total"];
$ds->movenext();
}
?>
<b><?=currency(1,false);?><?=float_opt($total,2)?> <?=currency(2,false);?></b>
</td>
<td>
<?
$total=0;
$sql="select user,total from commission where total<0 and user=".(int)$_SESSION["people_id"]." and status=1";
$ds->open($sql);
while(!$ds->eof)
{
$total+=$ds->row["total"];
$ds->movenext();
}
?>
<b><?=currency(1,false);?><?=float_opt((-1*$total),2)?> <?=currency(2,false);?></b>

</td>
</tr>
</table>
<br>
<p><b><?=word_lang("Balance threshold for payout")?>:</b></p>
<?
$sql="select payout_limit from users where id_parent=".$_SESSION["people_id"];
$ds->open($sql);
if(!$ds->eof)
{
?>
<?=currency(1,false)?><?=float_opt($ds->row["payout_limit"],2)?> <?=currency(2,false)?>
<br><br>
<?
}
?>


<?if($global_settings["credits"]){?>
<p><b><?=word_lang("price")?>:</b></p>

1 Credit = <?=currency(1,false)?><?=float_opt($global_settings["payout_price"],2)?> <?=currency(2,false)?>


<br><br>
<?}?>
