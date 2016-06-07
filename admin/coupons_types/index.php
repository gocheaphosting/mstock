<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_couponstypes");

?>
<? include("../inc/begin.php");?>








<h1><?=word_lang("types of coupons")?>:</h1>

<p>Here you can set a discount/coupon for the customers. The coupons can be sent to a buyer automatically in the next cases:</p>

<ul>
<li><b><?=word_lang("new signup")?></b>. When a user registers on the site</li>
<li><b><?=word_lang("new order")?></b>. When a user orders the second time.</li>

</ul>

<p>
4 types of the coupons are available:
</p>

<ul>
<li><b><?=word_lang("bonus")?></b> in Credits</li>
<li><b><?=word_lang("total discount")?></b></li>
<li><b><?=word_lang("percentage discount")?></b></li>
<li><b>Free download link</b></li>
</ul>


<br>

<?
$sql="select id_parent,title,days,total,percentage,url,events,ulimit,bonus from coupons_types";
$rs->open($sql);
if(!$rs->eof)
{
	$tr=1;
	?>
	<form method="post" action="change.php">
	<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover">
	<tr>
	<th><b><?=word_lang("title")?>:</b></th>
	<th><b><?=word_lang("limit of usage")?>:</b></th>
	<th><b><?=word_lang("days till expiration")?>:</b></th>
	<th><b><?=word_lang("discount")?>:</b></th>
	<th><b>Free download link:</b></th>
	<th><b><?=word_lang("events")?>:</b></th>
	<th><b><?=word_lang("bonus")?>:</b></th>
	<th><b><?=word_lang("quantity")?>:</b></th>
	<th><b><?=word_lang("delete")?></b></th>
	</tr>
	<?
	while(!$rs->eof)
	{
	
		$discount=0;
		$discount_type="total";
		if($rs->row["total"]!=0)
		{
			$discount=$rs->row["total"];
			$discount_type="total";
		}
		if($rs->row["percentage"]!=0)
		{
			$discount=$rs->row["percentage"];
			$discount_type="percentage";
		}
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?>>
		<td><input name="title<?=$rs->row["id_parent"]?>" type="text" style="width:150px" value="<?=$rs->row["title"]?>"></td>
		<td><input name="ulimit<?=$rs->row["id_parent"]?>" type="text" style="width:100px" value="<?=$rs->row["ulimit"]?>"></td>
		<td><input name="days<?=$rs->row["id_parent"]?>" type="text" style="width:100px" value="<?=$rs->row["days"]?>"></td>
		<td><input name="discount<?=$rs->row["id_parent"]?>" type="text" style="width:40px;display:inline" value="<?=$discount?>">&nbsp;<select name="discount_type<?=$rs->row["id_parent"]?>" style="width:80px;display:inline">
			<option value="percentage" <?if($discount_type=="percentage"){echo(" selected");}?>>%</option>
			<option value="total" <?if($discount_type=="total"){echo(" selected");}?>><?=currency(1)?><?=currency(2)?></option>
		</select>
		</td>
		<td><input name="url<?=$rs->row["id_parent"]?>" type="text" style="width:100px" value="<?=$rs->row["url"]?>"></td>
		<td>
		<select name="events<?=$rs->row["id_parent"]?>" style="width:150px">
			<option value="New Order" <?if($rs->row["events"]=="New Order"){echo(" selected");}?>><?=word_lang("new order")?></option>
			<option value="New Signup" <?if($rs->row["events"]=="New Signup"){echo(" selected");}?>><?=word_lang("new signup")?></option>
		</select>
		</td>
		<td><input name="bonus<?=$rs->row["id_parent"]?>" type="text" style="width:50px" value="<?=$rs->row["bonus"]?>"></td>
		<td>
		<?
		$coupons_count=0;
		$sql="select count(id_parent) as coupons_count from coupons where coupon_id=".$rs->row["id_parent"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$coupons_count=$ds->row["coupons_count"];
		}
		?><a href="../coupons/index.php?coupon_id=<?=$rs->row["id_parent"]?>"><?=$coupons_count?></a>
		</td>
		<td>
		<div class="link_delete"><a href='delete.php?id=<?=$rs->row["id_parent"]?>' onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div>
		</td>
		</tr>
		<?
	$tr++;
	$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
	<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin:10px 0px 0px 6px">
	</form><br>
	<?
}
?>










<h2><?=word_lang("new")?>:</h2>

<form method="post" action="add.php">
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
<tr>
	<th><b><?=word_lang("title")?>:</b></th>
	<th><b><?=word_lang("limit of usage")?>:</b></th>
	<th><b><?=word_lang("days till expiration")?>:</b></th>
	<th><b><?=word_lang("discount")?>:</b></th>
	<th><b>Free download link:</b></th>
	<th><b><?=word_lang("events")?>:</b></th>
	<th><b><?=word_lang("bonus")?>:</b></th>
</tr>
<tr>
		<td><input name="title" type="text" style="width:150px" value="New"></td>
		<td><input name="ulimit" type="text" style="width:100px" value="1"></td>
		<td><input name="days" type="text" style="width:100px" value="30"></td>
		<td><input name="discount" type="text" style="width:40px;display:inline" value="0">&nbsp;<select name="discount_type" style="width:80px;display:inline">
			<option value="percentage">%</option>
			<option value="total"><?=currency(1)?><?=currency(2)?></option>
		</select>
		</td>
		<td><input name="url" type="text" style="width:100px" value=""></td>
		<td>
		<select name="events" style="width:150px">
			<option value="New Order"><?=word_lang("new order")?></option>
			<option value="New Signup"><?=word_lang("new signup")?></option>
		</select>
		</td>
		<td><input name="bonus" type="text" style="width:50px" value="0"></td>
</tr>
</table>
</div></div></div></div></div></div></div></div>
<input type="submit" class="btn btn-success" value="<?=word_lang("add")?>" style="margin:10px 0px 0px 6px">
</form>

















<? include("../inc/end.php");?>