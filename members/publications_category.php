<?if(!isset($_SESSION["people_id"])){exit;}?>
<?if(!defined("site_root")){exit();}?>
<?if($global_settings["userupload"]==0){exit;}?>
<?

$sql="select id_parent,title,description,photo,published,userid from category where userid=".(int)$_SESSION["people_id"]." order by id_parent desc";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<table border="0" cellpadding="5" cellspacing="0" class="profile_table">
		<tr>
			<th><b><?=word_lang("title")?>:</b></th>
			<th></th>
			<th><b><?=word_lang("description")?>:</b></th>
			<th><b><?=word_lang("status")?>:</b></th>
			<th></th>
			<th></th>
		</tr>
	<?
	$n=0;
	$tr=1;
	while(!$rs->eof)
	{
		if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
		{
			$preview="../images/not_found.gif";
			if($rs->row["photo"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["photo"]))
			{
				$preview=$rs->row["photo"];
			}
			
			$url=category_url($rs->row["id_parent"]);
			?>
			<tr valign="top"  <?if($tr%2==0){echo("class='snd'");}?>> 
				<td><?if($rs->row["published"]==1){?><a href="<?=$url?>"><?}?><img src="<?=$preview?>" border="0"></a></td>
				<td><?if($preview!="" and $rs->row["published"]==1){?><a href="<?=$url?>"><?}?><?=$rs->row["title"]?></a></td>
				<td><?=$rs->row["description"]?></td>
				<td><?if($rs->row["published"]==1){echo("<div class='link_approved'>".word_lang("approved")."</div>");}else{echo("<div class='link_pending'>".word_lang("pending")."</div>");}?></td>
			<td style="padding-left:20px">
				<div class="link_edit"><a href='filemanager_category.php?id=<?=$rs->row["id_parent"]?>&d=1'><?=word_lang("edit")?></a></div>
			</td>
			<td style="padding-left:20px">
				<?
				$sql="select id,id_parent from structure where id_parent=".$rs->row["id_parent"];
				$dr->open($sql);
				if($dr->eof)
				{
					?>
					<div class="link_delete"><a href='delete_category.php?id=<?=$rs->row["id_parent"]?>' onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div>
					<?
				}
				?>
			</td>
			</tr>

			<?
		}
		$n++;
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
	<?
	echo(paging($n,$str,$kolvo,$kolvo2,"publications.php","&d=1"));
}
else
{
	echo("<b>".word_lang("not found")."</b>");
}
?>