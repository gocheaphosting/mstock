<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>

<h1><?=word_lang("affiliate")?> &mdash; <?=word_lang("earning")?></h1>

<?

if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

$kolvo=$global_settings["k_str"];

$kolvo2=k_str2;


$sql="select userid,types,types_id,rates,total,data from affiliates_signups where aff_referal=".(int)$_SESSION["people_id"]." and total>0 order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
?>
<table border=0 cellpadding=0 cellspacing=0 class=profile_table width="100%">
<tr>
<th class='hidden-phone hidden-tablet'><b><?=word_lang("title")?></b></th>
<th><b><?=word_lang("date")?></b></th>
<th><b><?=word_lang("commission")?></b></th>
</tr>
<?
$n=0;
$tr=1;
$total=0;
while(!$rs->eof)
{







if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td class='hidden-phone hidden-tablet'><div class="link_order"><?=word_lang($rs->row["types"])?> # <?=$rs->row["types_id"]?></div></td>
<td><div class="link_date"><?=date(date_format,$rs->row["data"])?></div></td>
<td><span class="price"><b><?=currency(1,false);?><?=float_opt($rs->row["total"]*$global_settings["payout_price"],2)?> <?=currency(2,false);?></b></span> (<?=$rs->row["rates"]?>%)</td>
</tr>
<?
}
$n++;
$tr++;
$total+=$rs->row["total"]*$global_settings["payout_price"];
$rs->movenext();
}
?>
</table>

<p><b><?=word_lang("total")?>:</b> <span class="price"><b><?=currency(1,false);?><?=float_opt($total,2)?> <?=currency(2,false);?></b></span></p>


<?
echo(paging($n,$str,$kolvo,$kolvo2,"affiliate.php","&d=2"));
}
else
{
echo("<b>".word_lang("not found")."</b>");
}
?>