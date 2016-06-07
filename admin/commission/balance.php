<?
//Check access
admin_panel_access("orders_commission");


//Payments settings
include($_SERVER["DOCUMENT_ROOT"].site_root."/members/payments_settings.php");

//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];

//Количество страниц на странице
$kolvo2=k_str2;


$sql="select user,sum(total) as sum_total from commission group by user order by sum_total desc";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("user")?></b></th>

	<th class="hidden-phone hidden-tablet"><b><?=word_lang("earning")?></b></th>
	<th class="hidden-phone hidden-tablet"><b><?=word_lang("refund")?></b></th>
	<th><b><?=word_lang("balance")?></b></th>
	<th><b><?=word_lang("Balance threshold for payout")?></b></th>
	<th><b><?=word_lang("payout method")?></b></th>
	</tr>
	<?
	$tr=1;
	$n=0;
	while(!$rs->eof)
	{
		if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
		{
		?>
			<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
			<td><?
			$sql="select id_parent,login from users where id_parent=".$rs->row["user"];
			$ds->open($sql);
			if(!$ds->eof)
			{
				?>
				<div class="link_user"><a href="../customers/content.php?id=<?=$ds->row["id_parent"]?>"><?=$ds->row["login"]?></a></div>
				<?
			}
			?></td>
			<td class="hidden-phone hidden-tablet">
			<?
			$total1=0;
			$sql="select user,total from commission where total>0 and user=".$rs->row["user"];
			$ds->open($sql);
			while(!$ds->eof)
			{
				$total1+=$ds->row["total"];
				$ds->movenext();
			}
			?>
			<?=currency(1,false);?><?=float_opt($total1,2)?> <?=currency(2,false);?>
			</td>
			<td class="hidden-phone hidden-tablet">
			<?
			$total2=0;
			$sql="select user,total,status from commission where total<0 and user=".$rs->row["user"];
			$ds->open($sql);
			while(!$ds->eof)
			{
				if($ds->row["status"]==1)
				{
					$total2+=$ds->row["total"];
				}
				$ds->movenext();
			}
			?>
			<?=currency(1,false);?><?=float_opt((-1*$total2),2)?> <?=currency(2,false);?>
			</td>
			<td><span class="price"><b><?=currency(1,false);?><?=float_opt($total1+$total2,2)?> <?=currency(2,false);?></b></span></td>
			<td>
			<?
			$sql="select payout_limit from users where id_parent=".$rs->row["user"];
			$ds->open($sql);
			if(!$ds->eof)
			{
				if($ds->row["payout_limit"]<=$total1+$total2)
				{
					echo("<span class='label label-danger'>");
				}
			?>
			<?=currency(1,false)?><?=float_opt($ds->row["payout_limit"],2)?> <?=currency(2,false)?>
			<?
			}
			?>
			</td>
			<td>
				<?
				$sql="select paypal,moneybookers,dwolla,qiwi,webmoney,bank_name,bank_account,payson from users where id_parent=".$rs->row["user"];
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="select * from payout where activ=1";
					$ds->open($sql);
					while(!$ds->eof)
					{
						
							if($ds->row["svalue"]=="bank")
							{
								if($dr->row["bank_name"]!="" and $dr->row["bank_account"]!="")
								{
									?>
										<a href="payout.php?user=<?=$rs->row["user"]?>&method=<?=$ds->row["svalue"]?>" target="_blank"  class="link_<?=$ds->row["svalue"]?>"><?=str_replace(" account","",$ds->row["title"])?>:&nbsp;<?=$dr->row["bank_name"]?>&#187;</a>&nbsp;&nbsp;&nbsp;
									<?		
								}
							}
							else
							{
								$t="site_".$ds->row["svalue"]."_account";
								$tt=$$t;
								if($dr->row[$ds->row["svalue"]]!="")
								{
									?>
										<a href="payout.php?user=<?=$rs->row["user"]?>&method=<?=$ds->row["svalue"]?>" target="_blank"  class="link_<?=$ds->row["svalue"]?>"><?=str_replace(" account","",$ds->row["title"])?>&#187;</a>&nbsp;&nbsp;&nbsp;
									<?
								}
							}
						$ds->movenext();
					}
				}
				?>
				<a href="payout.php?user=<?=$rs->row["user"]?>&method=other" target="_blank" class="link_other"><?=word_lang("other")?> &#187;</a>&nbsp;&nbsp;&nbsp;
			</td>
			</tr>
			<?
		}
		$tr++;
		$n++;
		$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
	<?
	echo(paging($n,$str,$kolvo,$kolvo2,"index.php","&d=3"));
}
else
{
	echo("<b>".word_lang("not found")."</b>");
}
?>