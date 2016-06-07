<?
//Check access
admin_panel_access("settings_pwinty");

if(!defined("site_root")){exit();}
?>

<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">
<a href="http://www.pwinty.com/"><b>Pwinty.com</b></a> provides an easy way to let your users order photo prints from within your website. <br><br>

The users order prints on your site. Pwinty prints them and deliver to your customers.<br><br>

At the moment they are offering photo prints and posters of different sizes. All printing is done on high-quality commercial photo equipment, so your prints will look great too.
</div>

<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">
<?
$sql="select * from pwinty";
$rs->open($sql);
if(!$rs->eof)
{
?>
<form method="post" action="change.php">

<div class="admin_field">
	<span>Merchant ID</span>
	<input type="text" name="account" value="<?=$rs->row["account"]?>" style="width:300px">
</div>

<div class="admin_field">
	<span>API Key</span>
	<input type="text" name="password" value="<?=$rs->row["password"]?>" style="width:300px">
</div>

<div class="admin_field">
	<span>Order ID</span>
	<input type="text" name="order_number" value="<?=$rs->row["order_number"]?>" style="width:100px">
	<div class="smalltext">Starting with this ID the orders will be sent to Pwinty.</div>
</div>

<div class="admin_field">
	<span>Test mode</span>
	<input type="checkbox" name="testmode" <?if($rs->row["testmode"]==1){echo("checked");}?>>
</div>



<div class="admin_field">
	<span>Use Tracked Shipping</span>
	<input type="checkbox" name="usetrackedshipping" <?if($rs->row["usetrackedshipping"]==1){echo("checked");}?>>
</div>

<div class="admin_field">
	<span>Payment</span>
	<select name="payment" style="width:300px">
		<option value="InvoiceMe" <?if($rs->row["payment"]=="InvoiceMe"){echo("selected");}?>>Invoice Me</option>
		<option value="InvoiceRecipient" <?if($rs->row["payment"]=="InvoiceRecipient"){echo("selected");}?>>Invoice Recipient</option>
	</select>
</div>

<div class="admin_field">
	<span>Quality Level</span>
	<select name="qualitylevel" style="width:300px">
		<option value="Standard" <?if($rs->row["qualitylevel"]=="Standard"){echo("selected");}?>>Standard</option>
		<option value="Pro" <?if($rs->row["qualitylevel"]=="Pro"){echo("selected");}?>>Pro</option>
	</select>
</div>

<div class="admin_field">
	<span>Photo resizing</span>
	<select name="photoresizing" style="width:300px">
		<option value="Crop" <?if($rs->row["photoresizing"]=="Crop"){echo("selected");}?>>Crop</option>
		<option value="ShrinkToFit" <?if($rs->row["photoresizing"]=="ShrinkToFit"){echo("selected");}?>>ShrinkToFit</option>
		<option value="ShrinkToExactFit" <?if($rs->row["photoresizing"]=="ShrinkToExactFit"){echo("selected");}?>>ShrinkToExactFit</option>
	</select>
</div>

<div class="admin_field">
	<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>">
</div>

</form>
<?
}
?>
</div>


<div class="subheader"><?=word_lang("prints")?></div>
<div class="subheader_text">
<?
$id_list="";
$sql="select id_parent from prints";
$rs->open($sql);
while(!$rs->eof)
{
	$sql="select print_id from pwinty_prints where print_id=".$rs->row["id_parent"];
	$ds->open($sql);
	if($ds->eof)
	{
		$sql="insert into pwinty_prints (print_id,activ) values (".$rs->row["id_parent"].",0)";
		$db->execute($sql);
	}
	
	if($id_list!="")
	{
		$id_list.="and print_id<>".$rs->row["id_parent"];
	}
	
	$rs->movenext();
}

$sql="delete from pwinty_prints where ".$id_list;
$db->execute($sql);


$sql="select id_parent,title from prints order by priority";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<form method="post" action="change_prints.php">
	<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover">
	<tr>
	<th>*<?=word_lang("title")?> in Pwinty system</th>
	<th><?=word_lang("prints")?></th>
	<th><?=word_lang("enabled")?></th>
	</tr>
	<?
	while(!$rs->eof)
	{
		$sql="select title,print_id,activ from pwinty_prints where print_id=".$rs->row["id_parent"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			?>
			<tr>
				<td><input type="text" name="title<?=$rs->row["id_parent"]?>" value="<?=$ds->row["title"]?>" style="width:100px"></td>
				<td><?=$rs->row["title"]?></td>
				<td><input type="checkbox" name="print<?=$rs->row["id_parent"]?>" <?if($ds->row["activ"]==1){echo("checked");}?>></td>
			</tr>
			<?
		}
		
		$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
	<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin:10px 0px 20px 6px">
	<p class="smalltext">* It must be like 4x4, 4x6 for Prints. P16x24, P18x18 - for Posters. C10x12,C12x12 - for Canvases.</p>
	</form>
	<?
}
else
{
	echo("<p><b>".word_lang("not found")."</b></p>");
}
?>

</div>