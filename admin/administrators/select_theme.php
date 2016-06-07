<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_administrators");
?>
<? include("../inc/begin.php");?>






<h1><?=word_lang("Admin panel")?> &mdash; <?=word_lang("select skin")?>:</h1>

<form method="post" action="change.php">

<?
$sql="select * from templates_admin order by id";
$rs->open($sql);
if(!$rs->eof)
{
	$tr=1;
	?>
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("active")?>:</b></th>
	<th><b><?=word_lang("name")?>:</b></th>
	<th><b><?=word_lang("folder")?>:</b></th>	
	</tr>
	<?
	while(!$rs->eof)
	{	
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
		<td><input name="activ" type="radio" value="<?=$rs->row["id"]?>" <?if($rs->row["activ"]==1){echo("checked");}?>></td>
		<td style="width:70%"><b><?=$rs->row["title"]?></b></td>
		<td><?=site_root?>/admin/templates_admin/<?=$rs->row["folder"]?>/</td>
		
		</tr>
		<?
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
<?
}
?>

<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>" style="margin:10px 0px 0px 6px">





</form>

<?
if($admin_template == "adminlte")
{
	?>
	<br><br>
	<h1>Admin LTE &mdash; <?=word_lang("home page")?></h1>
	<form method="post" action="change_adminlte.php">
	
	

	<?
	$sql="select id, title, left_right, activ, priority from templates_admin_home order by left_right,priority";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$tr=1;
		?>
		<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
		<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
		<tr>
		<th><b><?=word_lang("active")?>:</b></th>
		<th><b><?=word_lang("name")?>:</b></th>
		<th><b><?=word_lang("priority")?>:</b></th>	
		<th><b><?=word_lang("columns")?>:</b></th>	
		</tr>
		<?
		while(!$rs->eof)
		{	
			$tab_name=preg_replace("/[^a-z_]/i","",strtolower(str_replace(" ","_",$rs->row["title"])));

			?>
			<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
			<td><input name="active<?=$rs->row["id"]?>" type="checkbox" value="1" <?if($rs->row["activ"]==1 and @$_COOKIE["delete_".$tab_name]!=1){echo("checked");}?>></td>
			<td style="width:50%"><?=$rs->row["title"]?></td>
			<td><input name="priority<?=$rs->row["id"]?>" type="text" value="<?=$rs->row["priority"]?>" style="width:100px"></td>
			<td>
				<select style="width:100px" name="left_right<?=$rs->row["id"]?>">
					<option value="0" <?if($rs->row["left_right"]==0){echo("selected");}?>><?=word_lang("left")?></option>
					<option value="1" <?if($rs->row["left_right"]==1){echo("selected");}?>><?=word_lang("right")?></option>
				</select>
			</td>
			</tr>
			<?
			$tr++;
			$rs->movenext();
		}
		?>
		</table>
		</div></div></div></div></div></div></div></div>
	<?
	}
	?>
	
	<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>" style="margin:10px 0px 0px 6px">
	
	
	
	
	
	</form>
	<?
}
?>



<? include("../inc/end.php");?>