<?if(!defined("site_root")){exit();}?>

<ul>
<li>Segpay may be used only for <b>subscription or credits</b> payments. 
<li>You should create Package IDs and Product IDs at https://my.segpay.com/ (Create Packages -> Price Points) for each  your subscription and credits plans.


<li> Enter Post Back<br>
<b>2nd Trans Post URL:</b> <?=surl.site_root."/members/payments_segpay_go.php?product_id=< extra product_id >&product_type=< extra product_type >&approved=< approved >&trans_id=< tranid >"?>

</ul>

<table border=0 cellpadding=0 cellspacing=0>
<tr valign="top">
<td>
<form method="post" action="segpay_change.php">
<?
$sql="select * from gateway_segpay where subscription=0 and credits=0";
$rs->open($sql);
if(!$rs->eof)
{
?>

<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='enable' value="1" <?if($rs->row["activ"]==1){echo("checked");}?>>
</div>

<div class='admin_field'>
<span><?=word_lang("allow ipn")?>:</span>
<input type='checkbox' name='ipn' value="1" <?if($rs->row["ipn"]==1){echo("checked");}?>>
</div>
<?
}
?>
<input type="submit" value="<?=word_lang("change")?>" style="margin-top:3px">
</form>
</td>
<td style="padding-left:50px">

<form method="post" action="segpay_change2.php">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class=table_admin>
<tr valign="top">
<th><b><?=word_lang("subscription")?>/<?=word_lang("credits")?>:</b></th>
<th><b><?=word_lang("price")?></b></th>
<th><b>Package ID</b></th>
<th><b>Product ID</b></th>
<th><b>Test</b></th>
</tr>
<?



$tr=0;


$ids=" credits=0 and subscription<>0 ";
$sql="select * from subscription order by priority";
$rs->open($sql);
while(!$rs->eof)
{
	$sql="select * from gateway_segpay where subscription=".$rs->row["id_parent"];
	$ds->open($sql);
	if($ds->eof)
	{
		$sql="insert into gateway_segpay (url,ipn,activ,package_id,product_id,subscription,credits) values ('',0,0,0,0,".$rs->row["id_parent"].",0)";
		$db->execute($sql);
	}

	$sql="select * from gateway_segpay where subscription=".$rs->row["id_parent"];
	$ds->open($sql);
	if(!$ds->eof)
	{
		$ids.=" and subscription<>".$rs->row["id_parent"];
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?>>
		<td><?=$rs->row["title"]?></td>
		<td><?=currency(1,false)?><?=float_opt($rs->row["price"],2)?> <?=currency(2,false)?></td>
		<td><input type="text" name="package<?=$rs->row["id_parent"]?>_0" style="width:80px" value="<?=$ds->row["package_id"]?>"></td>
		<td><input type="text" name="product<?=$rs->row["id_parent"]?>_0" style="width:80px" value="<?=$ds->row["product_id"]?>"></td>
		<td><input type="button" class="btn" onclick="location.href='<?=segpay_url?>?x-eticketid=<?=$ds->row["package_id"]?>:<?=$ds->row["product_id"]?>'" value="<?=word_lang("buy")?>"></td>
		</tr>
		<?
		$tr++;
	}

	$rs->movenext();
}
$sql="delete from gateway_segpay where ".$ids;
$db->execute($sql);

if($global_settings["credits"])
{
	$ids=" credits<>0 and subscription=0 ";
	$sql="select * from credits order by priority";
	$rs->open($sql);
	while(!$rs->eof)
	{
		$sql="select * from gateway_segpay where credits=".$rs->row["id_parent"];
		$ds->open($sql);
		if($ds->eof)
		{
			$sql="insert into gateway_segpay (url,ipn,activ,package_id,product_id,subscription,credits) values ('',0,0,0,0,0,".$rs->row["id_parent"].")";
			$db->execute($sql);
		}

		$sql="select * from gateway_segpay where credits=".$rs->row["id_parent"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$ids.=" and credits<>".$rs->row["id_parent"];
			?>
			<tr <?if($tr%2==0){echo("class='snd'");}?>>
			<td><?=$rs->row["title"]?></td>
			<td><?=currency(1,false)?><?=float_opt($rs->row["price"],2)?> <?=currency(2,false)?></td>
			<td><input type="text" name="package0_<?=$rs->row["id_parent"]?>" style="width:80px" value="<?=$ds->row["package_id"]?>"></td>
			<td><input type="text" name="product0_<?=$rs->row["id_parent"]?>" style="width:80px" value="<?=$ds->row["product_id"]?>"></td>
			<td><input type="button" class="btn" onclick="location.href='<?=segpay_url?>?x-eticketid=<?=$ds->row["package_id"]?>:<?=$ds->row["product_id"]?>'" value="<?=word_lang("buy")?>"></td>
			</tr>
			<?
			$tr++;
		}
		$rs->movenext();
	}
	$sql="delete from gateway_segpay where ".$ids;
	$db->execute($sql);
}
?>

</table>
</div></div></div></div></div></div></div></div>
<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin:3px 0px 0px 6px">
</form>
</td>
</tr>
</table>


