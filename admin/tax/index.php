<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_taxes");

?>
<? include("../inc/begin.php");?>



<a class="btn btn-success toright" href="content.php"><i class="icon-book icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add")?></a>

<h1><?=word_lang("taxes")?>:</h1>






<?
$sql="select * from tax order by title,rate_all";
$rs->open($sql);
if(!$rs->eof)
{
	$tr=1;
	?>
	<form method="post" action="change.php">
	
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr"><table border="0" cellpadding="0" cellspacing="0" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("enabled")?>:</b></th>
	<th><b><?=word_lang("title")?>:</b></th>
	<th><b><?=word_lang("items")?>:</b></th>
	<th><b><?=word_lang("price includes tax")?>:</b></th>
	
	<th><?=word_lang("cost")?>:</th>
	<th><b><?=word_lang("regions")?>:</b></th>
	<th></th>
	<th></th>
	</tr>
	<?
	while(!$rs->eof)
	{	
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
		<td><input type="checkbox" name="enabled<?=$rs->row["id"]?>" <?if($rs->row["enabled"]==1){echo("checked");}?>></td>
		<td><input type="text" value="<?=$rs->row["title"]?>" name="title<?=$rs->row["id"]?>" style="width:200px"></td>
		<td nowrap>
			<input type="checkbox" name="files<?=$rs->row["id"]?>" <?if($rs->row["files"]==1){echo("checked");}?>>&nbsp;<?=word_lang("files")?><br>
			<input type="checkbox" name="credits<?=$rs->row["id"]?>" <?if($rs->row["credits"]==1){echo("checked");}?>>&nbsp;<?=word_lang("credits")?><br>
			<input type="checkbox" name="subscription<?=$rs->row["id"]?>" <?if($rs->row["subscription"]==1){echo("checked");}?>>&nbsp;<?=word_lang("subscription")?><br>
			<input type="checkbox" name="prints<?=$rs->row["id"]?>" <?if($rs->row["prints"]==1){echo("checked");}?>>&nbsp;<?=word_lang("prints")?><br>
		</td>
		<td align="center"><input name="price_include<?=$rs->row["id"]?>" type="checkbox" <?if($rs->row["price_include"]==1){echo("checked");}?>></td>
		<td><input name="rate_all<?=$rs->row["id"]?>" type="text" style="width:50px;display:inline" value="<?=$rs->row["rate_all"]?>">&nbsp;<select name="rate_all_type<?=$rs->row["id"]?>" style="width:60px;display:inline" class="form-control">
		<option value="1" <?if($rs->row["rate_all_type"]==1){echo("selected");}?>>%</option>
		<option value="2" <?if($rs->row["rate_all_type"]==2){echo("selected");}?>>$</option>
		</select></td>
		<td><span class="gray">
		<?
		if($rs->row["regions"]==0)
		{
			echo(word_lang("everywhere"));
		}
		else
		{
			$sql="select country,state from tax_regions where id_parent=".$rs->row["id"];
			$ds->open($sql);
			$n=0;
			while(!$ds->eof)
			{
				if($n!=0)
				{
					echo(", ");
				}
				echo($ds->row["country"]);
				if($ds->row["state"]!="")
				{
					echo("(".$ds->row["state"].")");
				}
				$n++;
				$ds->movenext();
			}
		}
		?></span>
		</td>
		<td><div class="link_edit"><a href='content.php?id=<?=$rs->row["id"]?>'><?=word_lang("edit")?></a></td>
		<td><div class="link_delete"><a href='delete.php?id=<?=$rs->row["id"]?>'><?=word_lang("delete")?></a></div></td>
		</tr>
		<?
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
	
	
	
<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>" style="margin:15px 0px 0px 6px">





</form>
<?
}
else
{
 	echo("<p><b>".word_lang("not found")."</b></p>");
}
?>
















<? include("../inc/end.php");?>