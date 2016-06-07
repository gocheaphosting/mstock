<?if(!defined("site_root")){exit();}?>















<ul>
<li>ccBill may be used only for <b>subscription or credits</b> payments. 



<li>At <b>ccBill merchant account</b> (<a href="https://webadmin.ccbill.com/">https://webadmin.ccbill.com/</a>) go to <b>QuickLinks -> Account Setup -> Account Admin</b>


<li>Choose an existing <b>Subaccount</b>, or create new one.

<li>Create the same <b>subscription and credits types</b> as you have in photo video store admin panel.

<li>Create a <b>Form</b> for your subscription types.


<li>Go to <b>Modify Subaccount -> Advanced</b> and set:<br> 
    <b>Approval Post URL:</b><br>
     <a href="<?=surl?><?=site_root?>/members/payments_process.php?mode=notification&processor=ccbill"><?=surl?><?=site_root?>/members/payments_process.php?mode=notification&processor=ccbill</a><br>
    <b>Denial Post URL:</b><br>
     <a href="<?=surl?><?=site_root?>/members/payments_result.php?d=2"><?=surl?><?=site_root?>/members/payments_result.php?d=2</a>


</ul>
<br>

<table border=0 cellpadding=0 cellspacing=0>
<tr valign="top">
<td>
<form method="post" action="ccbill_change.php">
<?
$sql="select * from gateway_ccbill where subscription=0 and credits=0";
$rs->open($sql);
if(!$rs->eof)
{
?>



<div class='admin_field'>
<span><?=word_lang("account")?>:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>SubAccount:</span>
<input type='text' name='account2'  style="width:400px" value="<?=$rs->row["account2"]?>">
</div>

<div class='admin_field'>
<span>Form ID:</span>
<input type='text' name='account3'  style="width:400px" value="<?=$rs->row["account3"]?>">
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
<input type="submit" class="btn-btn-primary" value="<?=word_lang("change")?>" style="margin-top:3px">
</form>
</td>
<td style="padding-left:50px">

<form method="post" action="ccbill_change2.php">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class=table_admin>
<tr valign="top">
<th><b><?=word_lang("subscription")?>/<?=word_lang("credits")?>:</b></th>
<th><b><?=word_lang("price")?></b></td>
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
	$sql="select * from gateway_ccbill where subscription=".$rs->row["id_parent"];
	$ds->open($sql);
	if($ds->eof)
	{
		$sql="insert into gateway_ccbill (url,account,ipn,activ,product_id,subscription,credits) values ('','',0,0,0,".$rs->row["id_parent"].",0)";
		$db->execute($sql);
	}

	$sql="select * from gateway_ccbill where subscription=".$rs->row["id_parent"];
	$ds->open($sql);
	if(!$ds->eof)
	{
		$ids.=" and subscription<>".$rs->row["id_parent"];
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?>>
		<td><?=$rs->row["title"]?></td>
		<td><?=currency(1,false)?><?=float_opt($rs->row["price"],2)?> <?=currency(2,false)?></td>
		<td><input type="text" name="product<?=$rs->row["id_parent"]?>_0" style="width:80px" value="<?=$ds->row["product_id"]?>"></td>
		<td><input type="button" class="btn" onclick="location.href='<?=ccbill_url?>?clientAccnum=<?=$site_ccbill_account?>&clientSubacc=<?=$site_ccbill_account2?>&formName=<?=$site_ccbill_account3?>&subscriptionTypeId=<?=$ds->row["product_id"]?>'" value="<?=word_lang("buy")?>"></td>
		</tr>
		<?
		$tr++;
	}

	$rs->movenext();
	}
	$sql="delete from gateway_ccbill where ".$ids;
	$db->execute($sql);

if($global_settings["credits"])
{
	$ids=" credits<>0 and subscription=0 ";
	$sql="select * from credits order by priority";
	$rs->open($sql);
	while(!$rs->eof)
	{
		$sql="select * from gateway_ccbill where credits=".$rs->row["id_parent"];
		$ds->open($sql);
		if($ds->eof)
		{
			$sql="insert into gateway_ccbill (url,account,ipn,activ,product_id,subscription,credits) values ('','',0,0,0,0,".$rs->row["id_parent"].")";
			$db->execute($sql);
		}

		$sql="select * from gateway_ccbill where credits=".$rs->row["id_parent"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$ids.=" and credits<>".$rs->row["id_parent"];
			?>
			<tr <?if($tr%2==0){echo("class='snd'");}?>>
			<td><?=$rs->row["title"]?></td>
			<td><?=currency(1,false)?><?=float_opt($rs->row["price"],2)?> <?=currency(2,false)?></td>
			<td><input type="text" name="product0_<?=$rs->row["id_parent"]?>" style="width:80px" value="<?=$ds->row["product_id"]?>"></td>
			<td><input type="button" class="btn" onclick="location.href='https://wnu.com/secure/form.cgi?<?=$site_ccbill_account?>+<?=$ds->row["product_id"]?>+a+p'" value="<?=word_lang("buy")?>"></td>
			</tr>
			<?
			$tr++;
		}

		
		$rs->movenext();
	}
	$sql="delete from gateway_ccbill where ".$ids;
	$db->execute($sql);
}
?>

</table></div></div></div></div></div></div></div></div>
<input class="btn btn-primary" type="submit" value="<?=word_lang("change")?>" style="3px 0px 0px 6px">
</form>
</td>
</tr>
</table>







