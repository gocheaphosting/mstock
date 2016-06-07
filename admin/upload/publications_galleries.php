<?
//Check access
admin_panel_access("catalog_upload");
?>
<?if(!defined("site_root")){exit();}?>
<?
//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];

//Количество страниц на странице
$kolvo2=k_str2;


$sql="select * from galleries order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="5" cellspacing="0" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
		<th class="hidden-phone hidden-tablet"><?=word_lang("preview")?></th>
		<th><?=word_lang("title")?></th>
		<th><?=word_lang("user")?></th>
		<th class="hidden-phone hidden-tablet"><?=word_lang("edit")?></th>
		<th><?=word_lang("delete")?></th>
	</tr>
	<?
	$n=0;
	$tr=1;
	while(!$rs->eof)
	{
		if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
		{
			$gallery_count=0;
			$gallery_photo_default="";
			$sql="select id from galleries_photos where id_parent=".$rs->row["id"];
			$ds->open($sql);
			while(!$ds->eof)
			{
				$gallery_count++;
				$gallery_photo_default=$ds->row["id"];
			
				$ds->movenext();
			}
			
			if($gallery_photo_default!="")
			{
				$gallery_photo=site_root."/content/galleries/".$rs->row["id"]."/thumb".$gallery_photo_default.".jpg";
			}
			else
			{
				$gallery_photo=site_root."/images/not_found.gif";
			}

			$url="index.php?d=7&id=".$rs->row["id"];
			
			


			?>
			<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
			<td class="hidden-phone hidden-tablet"><a href="<?=$url?>"><img src="<?=$gallery_photo?>" border="0"></a></td>
			<td><?=$rs->row["title"]?> [ <a href='<?=$url?>'><?=$gallery_count?> <?=word_lang("photos")?></a> ]<br>
			

			
			</td>


			<td><div class="link_user"><?
			$sql="select id_parent,login from users where id_parent=".$rs->row["user_id"];
			$ds->open($sql);
			if(!$ds->eof)
			{
				?>
				<a href="../customers/content.php?id=<?=$ds->row["id_parent"]?>"><?=$ds->row["login"]?></a>
				<?
			}
			?></div></td>

			<td class="hidden-phone hidden-tablet"><div class="link_edit"><a href='<?=$url?>'><?=word_lang("edit")?></a></div></td>
			<td>	<div class="link_delete"><a href='delete_gallery.php?id=<?=$rs->row["id"]?>' onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div></td>
			</tr>
			<?

		$tr++;
		$n++;
		}
		$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
	<?
	echo(paging($n,$str,$kolvo,$kolvo2,"index.php","&d=6"));
}
else
{
	echo("<b>".word_lang("not found")."</b>");
}
?>