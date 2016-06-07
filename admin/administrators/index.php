<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_administrators");
?>
<? include("../inc/begin.php");?>






<a class="btn btn-success toright" href="content.php"><i class="icon-user icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add")?></a>



<h1><?=word_lang("Administrators")?>:</h1>



<?
$sql="select id,login,name from people order by login";
$rs->open($sql);
if(!$rs->eof)
{
	$tr=1;
	?>
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("login")?>:</b></th>
	<th class="hidden-phone hidden-tablet"><b><?=word_lang("name")?>:</b></th>
	<th class="hidden-phone hidden-tablet"><b>IP:</b></th>
	<th class="hidden-phone hidden-tablet"><b><?=word_lang("date")?>:</b></th>
	<th></th>
	<th></th>
	<th></th>
	</tr>
	<?
	while(!$rs->eof)
	{	
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
		<td><div class="link_user"><?=$rs->row["login"]?></div></td>
		<td class="hidden-phone hidden-tablet"><?=$rs->row["name"]?></td>
		<?
		$item_date="";
		$item_ip="&nbsp;";
		$sql="select accessdate,ip from people_access where user=".$rs->row["id"]." order by accessdate desc";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$item_date=date(datetime_format,$ds->row["accessdate"]);
			$item_ip=$ds->row["ip"];
		}
		?>
		<td class="hidden-phone hidden-tablet"><div class="link_ip"><?=$item_ip?></div></td>
		<td class="gray hidden-phone hidden-tablet"><?=$item_date?></td>
		<td><div class="link_stats"><a href='stats.php?id=<?=$rs->row["id"]?>'><?=word_lang("stats")?></a></td>
		<td><div class="link_edit"><a href='content.php?id=<?=$rs->row["id"]?>'><?=word_lang("edit")?></a></td>
		<td>
		<?
		if($rs->row["id"]!=$_SESSION["user_id"])
		{
		?>
			<div class="link_delete"><a href='delete.php?id=<?=$rs->row["id"]?>'><?=word_lang("delete")?></a></div>
		<?
		}
		?>
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





<? include("../inc/end.php");?>