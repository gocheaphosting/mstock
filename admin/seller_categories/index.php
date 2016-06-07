<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_sellercategories");
?>
<? include("../inc/begin.php");?>







<a class="btn btn-success toright" href="content.php"><i class="icon-user icon-white fa fa-plus"></i> <?=word_lang("add")?></a>



<h1><?=word_lang("customer categories")?>:</h1>




<?
$sql="select id_parent,priority,name,percentage,percentage_prints,percentage_subscription,percentage_type,percentage_prints_type,percentage_subscription_type from user_category order by priority";
$rs->open($sql);
if(!$rs->eof)
{
	$tr=1;
	?>
	<form method="post" action="change.php">
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("priority")?>:</b></th>
	<th><b><?=word_lang("title")?>:</b></th>
	<th><b><?=word_lang("Commission")?> &mdash; <?=word_lang("order")?> (to seller):</b></th>
	<th><b><?=word_lang("Commission")?> &mdash; <?=word_lang("subscription")?> (to seller):</b></th>
	<th><b><?=word_lang("Commission")?> &mdash; <?=word_lang("prints")?> (to seller):</b></th>
	<th></th>
	<th></th>
	</tr>
	<?
	while(!$rs->eof)
	{
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
		<td align="center"><input name="priority<?=$rs->row["id_parent"]?>" type="text" style="width:40px" value="<?=$rs->row["priority"]?>"></td>
		<td><input name="title<?=$rs->row["id_parent"]?>" type="text" style="width:100px" value="<?=$rs->row["name"]?>"></td>
		<td>
			<input name="percentage<?=$rs->row["id_parent"]?>" type="text" style="width:50px;display:inline" value="<?if($rs->row["percentage_type"]==0){echo(round($rs->row["percentage"]));}else{echo(float_opt($rs->row["percentage"],2));}?>">
			<select name="percentage_type<?=$rs->row["id_parent"]?>" style="width:70px;display:inline">
				<option value="0" <?if($rs->row["percentage_type"]==0){echo("selected");}?>>%</option>
				<option value="1" <?if($rs->row["percentage_type"]==1){echo("selected");}?>><?=$currency_code1?></option>
			</select>
		</td>
		<td>
			<input name="percentage_subscription<?=$rs->row["id_parent"]?>" type="text" style="width:50px;display:inline" value="<?if($rs->row["percentage_subscription_type"]==0){echo(round($rs->row["percentage_subscription"]));}else{echo(float_opt($rs->row["percentage_subscription"],2));}?>">
			<select name="percentage_subscription_type<?=$rs->row["id_parent"]?>" style="width:70px;display:inline">
				<option value="0" <?if($rs->row["percentage_subscription_type"]==0){echo("selected");}?>>%</option>
				<option value="1" <?if($rs->row["percentage_subscription_type"]==1){echo("selected");}?>><?=$currency_code1?></option>
			</select>
		</td>
		<td>
			<input name="percentage_prints<?=$rs->row["id_parent"]?>" type="text" style="width:50px;display:inline" value="<?if($rs->row["percentage_prints_type"]==0){echo(round($rs->row["percentage_prints"]));}else{echo(float_opt($rs->row["percentage_prints"],2));}?>">
			<select name="percentage_prints_type<?=$rs->row["id_parent"]?>" style="width:70px;display:inline">
				<option value="0" <?if($rs->row["percentage_prints_type"]==0){echo("selected");}?>>%</option>
				<option value="1" <?if($rs->row["percentage_prints_type"]==1){echo("selected");}?>><?=$currency_code1?></option>
			</select>
		</td>
		<td><div class="link_edit"><a href='content.php?id=<?=$rs->row["id_parent"]?>'><?=word_lang("edit")?></a></td>
		<td>
		<div class="link_delete"><a href='delete.php?id=<?=$rs->row["id_parent"]?>'><?=word_lang("delete")?></a></div>
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





<? include("../inc/end.php");?>