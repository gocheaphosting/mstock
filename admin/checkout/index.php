<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_checkout");
?>
<? include("../inc/begin.php");?>






<h1><?=word_lang("checkout")?></h1>

<div class="box box_padding">



<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">
	<form method="post" action="change.php">

	<?if(!$global_settings["subscription_only"]){?>
		<div class='admin_field'>
			<div><?=word_lang("Require Billing Address for the Order Checkout")?>:</div>
			<input type="checkbox" name="checkout_order_billing" value="1" <?if($global_settings["checkout_order_billing"]){echo("checked");}?>>
		</div>
	
		<div class='admin_field'>
			<div><?=word_lang("Require Shipping Address for the Order Checkout")?>:</div>
			<input type="checkbox" name="checkout_order_shipping" value="1" <?if($global_settings["checkout_order_shipping"]){echo("checked");}?>>
		</div>
	<?}?>
	
	<?if($global_settings["credits"]){?>
		<div class='admin_field'>
			<div><?=word_lang("Require Billing Address for the Credits Checkout")?>:</div>
			<input type="checkbox" name="checkout_credits_billing" value="1" <?if($global_settings["checkout_credits_billing"]){echo("checked");}?>>
		</div>	
	<?}?>
	
	<?if($global_settings["subscription"]){?>
		<div class='admin_field'>
			<div><?=word_lang("Require Billing Address for the Subscription Checkout")?>:</div>
			<input type="checkbox" name="checkout_subscription_billing" value="1" <?if($global_settings["checkout_subscription_billing"]){echo("checked");}?>>
		</div>		
	<?}?>
	
	<div class='admin_field'>
	<input  class="btn btn-primary" type="submit" value="<?=word_lang("save")?>">
	</div>
	
	</form>
</div>




<div class="subheader"><?=word_lang("terms and conditions")?></div>
<div class="subheader_text">

<a class="btn btn-success toright" href="new.php"><i class="icon-file icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add")?></a>
<p>Here you can specify 'Terms and Conditions' checkboxes for the checkout process:</p>



<br>

<?
$sql="select * from terms order by types,priority";
$rs->open($sql);
if(!$rs->eof)
{
	$tr=1;
	?>
	<form method="post" action="change_terms.php">
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("type")?>:</b></th>
	<th><b><?=word_lang("priority")?>:</b></th>
	<th><b><?=word_lang("title")?>:</b></th>
	<th><b><?=word_lang("content")?></b></th>
	<th><b><?=word_lang("delete")?></b></th>
	</tr>
	<?
	while(!$rs->eof)
	{
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?>>
		<td>
			<select name="types<?=$rs->row["id"]?>" style="width:150px">
				<option value="1" <?if($rs->row["types"]==1){echo("selected");}?>><?=word_lang("order")?></option>
				<option value="2" <?if($rs->row["types"]==2){echo("selected");}?>><?=word_lang("credits")?></option>
				<option value="3" <?if($rs->row["types"]==3){echo("selected");}?>><?=word_lang("subscription")?></option>
			</select>
		</td>
		<td align="center"><input name="priority<?=$rs->row["id"]?>" type="text" style="width:40px" value="<?=$rs->row["priority"]?>"></td>
		<td><input name="title<?=$rs->row["id"]?>" type="text" style="width:250px" value="<?=$rs->row["title"]?>"></td>
		<td>
			<select name="page_id<?=$rs->row["id"]?>" style="width:250px">
				<option value="0"></option>
				<?
				$sql="select id_parent,title from pages order by title";
				$ds->open($sql);
				while(!$ds->eof)
				{
					$sel="";
					if($ds->row["id_parent"]==$rs->row["page_id"])
					{
						$sel="selected";
					}
					?>
					<option value="<?=$ds->row["id_parent"]?>" <?=$sel?>><?=$ds->row["title"]?></option>
					<?
					$ds->movenext();
				}
				?>
			</select>
		</td>
		<td><input name="delete<?=$rs->row["id"]?>" type="checkbox"></td>
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


</div>


</div>















<? include("../inc/end.php");?>