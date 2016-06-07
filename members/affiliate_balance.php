<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>

<h1><?=word_lang("affiliate")?></h1>


<p>You should use the affiliate link:</p>

<div class="t" style="margin-left:-6px"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr2"><b><?=surl?><?=site_root?>/?aff=<?=$_SESSION["people_id"]?></b></div></div></div></div></div></div></div></div>

<p>Example:</p>

<div class="t" style="margin-left:-6px"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr2">&lt;a href="<b><?=surl?><?=site_root?>/?aff=<?=$_SESSION["people_id"]?></b>"&gt;Photo Stock Site&lt;/a&gt;</div></div></div></div></div></div></div></div>

<br>

<h2><?=word_lang("stats")?></h2>

<?
$sql="select aff_commission_buyer,aff_commission_seller,aff_visits from users where id_parent=".(int)$_SESSION["people_id"];
$rs->open($sql);
if(!$rs->eof)
{
	$seller_qty=0;
	$seller_orders_qty=0;
	$seller_total=0;
	$buyer_qty=0;
	$buyer_orders_qty=0;
	$buyer_total=0;
	
	$sql="select seller,buyer,userid from affiliates_stats where aff_referal=".(int)$_SESSION["people_id"];
	$dr->open($sql);
	while(!$dr->eof)
	{
		if($dr->row["seller"]==1)
		{
			$seller_qty++;
			$sql="select total from affiliates_signups where userid=".$dr->row["userid"];
			$ds->open($sql);
			while(!$ds->eof)
			{
				$seller_total+=$ds->row["total"];
				$seller_orders_qty++;
				$ds->movenext();
			}
		}
		if($dr->row["buyer"]==1)
		{
			$buyer_qty++;
			$sql="select total from affiliates_signups where userid=".$dr->row["userid"];
			$ds->open($sql);
			while(!$ds->eof)
			{
				$buyer_total+=$ds->row["total"];
				$buyer_orders_qty++;
				$ds->movenext();
			}
		}
		$dr->movenext();
	}
?>
<table border=0 cellpadding=0 cellspacing=0 class=profile_table width="100%">
<tr>
<th><b><?=word_lang("sign up")?></b></th>
<th><b><?=word_lang("quantity")?></b></th>
<th><b><?=word_lang("commission")?></b></th>
<th><b><?=word_lang("orders")?></b></th>
<th><b><?=word_lang("total")?></b></th>
</tr>
<?if($global_settings["userupload"]!=0){?>
<tr>
<td><?=word_lang("seller")?></td>
<td><?=$seller_qty?></td>
<td><?=$rs->row["aff_commission_seller"]?>%</td>
<td><?=$seller_orders_qty?></td>
<td><?=currency(1,false);?><?=float_opt($seller_total,2)?> <?=currency(2,false);?></td>
</tr>
<?}?>
<tr class="snd">
<td><?=word_lang("buyer")?></td>
<td><?=$buyer_qty?></td>
<td><?=$rs->row["aff_commission_buyer"]?>%</td>
<td><?=$buyer_orders_qty?></td>
<td><?=currency(1,false);?><?=float_opt($buyer_total,2)?> <?=currency(2,false);?></td>
</tr>
<tr>
<td><?=word_lang("visits")?></td>
<td><?=$rs->row["aff_visits"]?></td>
<td></td>
<td></td>
<td></td>
</tr>
</table>
<?
}
?>

<br>

<h2><?=word_lang("balance")?></h2>

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
$sql="select total from affiliates_signups where aff_referal=".(int)$_SESSION["people_id"]." and status=1";
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
$sql="select total from affiliates_signups where total>0 and aff_referal=".(int)$_SESSION["people_id"]." and status=1";
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
$sql="select total from affiliates_signups where total<0 and aff_referal=".(int)$_SESSION["people_id"]." and status=1";
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


<?if($global_settings["credits"]){?>
<p style="float:right"><small> 
1 Credit = <?=currency(1,false)?><?=float_opt($global_settings["payout_price"],2)?> <?=currency(2,false)?>

</small>
</p>
<?}?>
