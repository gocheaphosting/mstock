<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>


<h1><?=word_lang("affiliate")?> &mdash; <?=word_lang("refund")?></h1>

<?
//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];

//Количество страниц на странице
$kolvo2=k_str2;


$sql="select total,data from affiliates_signups where total<0 and aff_referal=".(int)$_SESSION["people_id"]." and status=1 order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
?>
<table border=0 cellpadding=0 cellspacing=0 class=profile_table width="100%">
<tr>
<th><b><?=word_lang("date")?></b></th>
<th><b><?=word_lang("refund")?></b></th>
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
<td><div class="link_date"><?=date(date_format,$rs->row["data"])?></div></td>
<td><span class="price"><b><?=currency(1,false);?><?=float_opt((-1*$rs->row["total"]),2)?> <?=currency(2,false);?></b></span></td>
</tr>
<?
}
$n++;
$tr++;
$total+=$rs->row["total"];
$rs->movenext();
}
?>
</table>

<p><b><?=word_lang("total")?>:</b> <span class="price"><b><?=currency(1,false);?><?=float_opt((-1*$total),2)?> <?=currency(2,false);?></b></span></p>


<?
echo(paging($n,$str,$kolvo,$kolvo2,"affiliate.php","&d=3"));
}
else
{
echo("<b>".word_lang("not found")."</b>");
}
?>