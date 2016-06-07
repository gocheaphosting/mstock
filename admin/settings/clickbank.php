<?if(!defined("site_root")){exit();}?>














<ul>
<li>Clickbank may be used only for <b>subscription or credits</b> payments because you must add all products in your account on clickbank.com first. 



<li>You should go to your account on clickbank.com


<li>Choose  <b>Account Settings -> My Products</b> and create the same <b>subscription and credits types</b> as you have in photo video store admin panel.


<li>Go to <b>Account Settings -> My Site</b> and set:<br> 
    <b>Secret Key:</b><br>
    <br>
    <b>Instant Notification URL:</b><br>
      <a href="<?=surl?><?=site_root?>/members/payments_process.php?mode=notification&processor=clickbank"><?=surl?><?=site_root?>/members/payments_process.php?mode=notification&processor=clickbank</a>

</ul>
<br>

<table border=0 cellpadding=0 cellspacing=0>
<tr valign="top">
<td>
<form method="post" action="clickbank_change.php">
<?
$sql="select * from gateway_clickbank where subscription=0 and credits=0";
$rs->open($sql);
if(!$rs->eof)
{
?>



<div class='admin_field'>
<span><?=word_lang("account")?>:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>Secure Key:</span>
<input type='text' name='account2'  style="width:400px" value="<?=$rs->row["account2"]?>">
</div>

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

<form method="post" action="clickbank_change2.php">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class=table_admin>
<tr valign="top">
<th><b><?=word_lang("subscription")?>/<?=word_lang("credits")?>:</b></th>
<th><b><?=word_lang("price")?></b></th>
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
	$sql="select * from gateway_clickbank where subscription=".$rs->row["id_parent"];
	$ds->open($sql);
	if($ds->eof)
	{
		$sql="insert into gateway_clickbank (url,account,ipn,activ,product_id,subscription,credits) values ('','',0,0,0,".$rs->row["id_parent"].",0)";
		$db->execute($sql);
	}

	$sql="select * from gateway_clickbank where subscription=".$rs->row["id_parent"];
	$ds->open($sql);
	if(!$ds->eof)
	{
		$ids.=" and subscription<>".$rs->row["id_parent"];
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?>>
		<td><?=$rs->row["title"]?></td>
		<td><?=currency(1,false)?><?=float_opt($rs->row["price"],2)?> <?=currency(2,false)?></td>
		<td><input type="text" name="product<?=$rs->row["id_parent"]?>_0" style="width:80px" value="<?=$ds->row["product_id"]?>"></td>
		<td><input type="button" class="btn" onclick="location.href='<?=clickbank_url?>?clientAccnum=<?=$site_clickbank_account?>&clientSubacc=<?=$site_clickbank_account2?>&formName=<?=$site_clickbank_account3?>&subscriptionTypeId=<?=$ds->row["product_id"]?>'" value="<?=word_lang("buy")?>"></td>
		</tr>
		<?
		$tr++;
	}
	$rs->movenext();
}
$sql="delete from gateway_clickbank where ".$ids;
$db->execute($sql);

if($global_settings["credits"])
{
	$ids=" credits<>0 and subscription=0 ";
	$sql="select * from credits order by priority";
	$rs->open($sql);
	while(!$rs->eof)
	{
		$sql="select * from gateway_clickbank where credits=".$rs->row["id_parent"];
		$ds->open($sql);
		if($ds->eof)
		{
			$sql="insert into gateway_clickbank (url,account,ipn,activ,product_id,subscription,credits) values ('','',0,0,0,0,".$rs->row["id_parent"].")";
			$db->execute($sql);
		}

		$sql="select * from gateway_clickbank where credits=".$rs->row["id_parent"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$ids.=" and credits<>".$rs->row["id_parent"];
			?>
			<tr <?if($tr%2==0){echo("class='snd'");}?>>
			<td><?=$rs->row["title"]?></td>
			<td><?=currency(1,false)?><?=float_opt($rs->row["price"],2)?> <?=currency(2,false)?></td>
			<td><input type="text" name="product0_<?=$rs->row["id_parent"]?>" style="width:80px" value="<?=$ds->row["product_id"]?>"></td>
			<td><input type="button" class="btn" onclick="location.href='https://wnu.com/secure/form.cgi?<?=$site_clickbank_account?>+<?=$ds->row["product_id"]?>+a+p'" value="<?=word_lang("buy")?>"></td>
			</tr>
			<?
			$tr++;
		}
		$rs->movenext();
	}
	$sql="delete from gateway_clickbank where ".$ids;
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