<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_models");
?>
<? include("../inc/begin.php");?>




<a class="btn btn-success toright" href="content.php"><i class="icon-user icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add")?></a>




<h1><?=word_lang("model property release")?>:</h1>




<?
$sql="select id_parent,name,user from models order by name";
$rs->open($sql);
if(!$rs->eof)
{
	$tr=1;
	?>
	<form method="post" action="change.php">
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="0" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("title")?>:</b></th>
	<th><b><?=word_lang("user")?>:</b></th>
	<th></th>
	<th></th>
	</tr>
	<?
	while(!$rs->eof)
	{
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
		<td><input name="title<?=$rs->row["id_parent"]?>" type="text" style="width:250px" value="<?=$rs->row["name"]?>"></td>
		<td><div class="link_user">
			<?
			$sql="select id_parent from users where login='".$rs->row["user"]."'";
			$ds->open($sql);
			if(!$ds->eof)
			{
				echo("<a href='../customers/content.php?id=".$ds->row["id_parent"]."'>".$rs->row["user"]."</a>");
			}
			?></div>
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