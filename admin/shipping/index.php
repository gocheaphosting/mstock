<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_shipping");

?>
<? include("../inc/begin.php");?>



<a class="btn btn-success toright" href="content.php"><i class="icon-plane icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add")?></a>

<h1><?=word_lang("shipping")?>:</h1>




<?
$sql="select * from shipping order by title";
$rs->open($sql);
if(!$rs->eof)
{
	$tr=1;
	?>
	<form method="post" action="change.php">
	
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("enabled")?>:</b></th>
	<th><b><?=word_lang("title")?>:</b></th>
	<th><b><?=word_lang("shipping time")?>:</b></th>
	<th><b><?=word_lang("regions")?>:</b></th>
	<th></th>
	<th></th>
	</tr>
	<?
	while(!$rs->eof)
	{	
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
		<td><input type="checkbox" name="activ<?=$rs->row["id"]?>" <?if($rs->row["activ"]==1){echo("checked");}?>></td>
		<td><input type="text" value="<?=$rs->row["title"]?>" name="title<?=$rs->row["id"]?>" style="width:200px"></td>
		<td><input type="text" value="<?=$rs->row["shipping_time"]?>" name="shipping_time<?=$rs->row["id"]?>" style="width:100px"></td>
		<td><span class="gray">
		<?
		if($rs->row["regions"]==0)
		{
			echo(word_lang("everywhere"));
		}
		else
		{
			$sql="select country,state from shipping_regions where id_parent=".$rs->row["id"];
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