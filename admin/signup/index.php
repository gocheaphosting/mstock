<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_signup");
?>
<? include("../inc/begin.php");?>






<h1><?=word_lang("sign up")?></h1>



<div class="box box_padding">
<div class="subheader"><?=word_lang("Sign up fields")?></div>
<div class="subheader_text">

	<form method="post" action="change_fields.php">
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("title")?>:</b></th>
	<th><b><?=word_lang("priority")?>:</b></th>	
	<th><b><?=word_lang("required")?>:</b></th>
	<th><b><?=word_lang("columns")?></b></th>
	<th><b><?=word_lang("sign up")?></b></th>
	<th><b><?=word_lang("my profile")?></b></th>
	</tr>
	
	<?
		$tr=1;
		$sql="select * from users_fields   group by field_name order by columns,priority";
		$rs->open($sql);
		while(!$rs->eof)
		{
			?>
			<tr <?if($tr%2==0){echo("class='snd'");}?>>
				<td><?if($rs->row["required"]==1){echo("<b>");}?><?=word_lang($rs->row["title"])?><?if($rs->row["required"]==1){echo("</b>");}?></td>
				<td><input name="priority<?=$rs->row["id"]?>" type="text" value="<?=$rs->row["priority"]?>" style="width:50px"></td>
				<td><input name="required<?=$rs->row["id"]?>" type="checkbox" <?if($rs->row["required"]==1){echo("checked");}?> value="1" <?if($rs->row["field_name"]=="login" or $rs->row["field_name"]=="password" or $rs->row["field_name"]=="email"){echo("readonly onclick='return false'");}?>></td>
				<td>
					<select style="width:100px" name="columns<?=$rs->row["id"]?>">
						<option value="0" <?if($rs->row["columns"]==0){echo("selected");}?>><?=word_lang("left")?></option>
						<option value="1" <?if($rs->row["columns"]==1){echo("selected");}?>><?=word_lang("right")?></option>
					</select>
				</td>
				<td><input name="signup<?=$rs->row["id"]?>" <?if($rs->row["signup"]==1){echo("checked");}?> type="checkbox" value="1" <?if($rs->row["field_name"]=="login" or $rs->row["field_name"]=="password" or $rs->row["field_name"]=="email"){echo("readonly onclick='return false'");}?>></td>
				<td><input name="profile<?=$rs->row["id"]?>" <?if($rs->row["profile"]==1){echo("checked");}?> type="checkbox" value="1" <?if($rs->row["field_name"]=="login" or $rs->row["field_name"]=="password" or $rs->row["field_name"]=="email"){echo("readonly onclick='return false'");}?>></td>
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

</div>



<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">


<table border="0" cellpadding="0" cellspacing="0">
<tr valign="top">
<td>



<form method="post" action="change2.php">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:300px">
<tr>
<th></th>
<th><b><?=word_lang("type")?>:</b></th>
</tr>

<tr>
<td align="center"><input name="param" value="1" type="radio" <?if($global_settings["user_signup"]==1){echo("checked");}?>></td>
<td class="big">Simple Sign up</td>
</tr>

<tr class="snd">
<td align="center"><input name="param" value="2" type="radio" <?if($global_settings["user_signup"]==2){echo("checked");}?>></td>
<td class="big">Several steps Sign up</td>
</tr>

</table>
</div></div></div></div></div></div></div></div>
<input type="submit"  class="btn btn-primary" value="<?=word_lang("change")?>" style="margin:10px 0px 0px 6px">
</form>

</td>
<td style="padding-left:50px">




<?
$tr=1;
$sql="select * from users_settings";
$rs->open($sql);
if(!$rs->eof)
{
?>
<form method="post" action="change.php">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:300px">
<tr>
<th></th>
<th><b><?=word_lang("sign up")?>:</b></th>
</tr>
<?
while(!$rs->eof)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td align="center"><input name="activ" value="<?=$rs->row["svalue"]?>" type="radio" <?if($rs->row["activ"]==1){echo("checked");}?>></td>
<td class="big"><?=word_lang($rs->row["title"])?></td>
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



</td>
</tr>
</table>


</div>

</div>





<? include("../inc/end.php");?>