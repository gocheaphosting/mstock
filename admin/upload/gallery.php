<?
//Check access
admin_panel_access("catalog_upload");
?>
<?if(!defined("site_root")){exit();}?>


   <script type="text/javascript">
    $(function() {
        $('.photo_preview a').lightBox();
    });
    </script>


<?
//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];

//Количество страниц на странице
$kolvo2=k_str2;


$sql="select title, description from galleries where id=".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		echo("<h2>".$rs->row["title"]."</h2>");
	}


$sql="select * from galleries_photos where id_parent=".(int)$_GET["id"]." order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="5" cellspacing="0" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
		<th class="hidden-phone hidden-tablet"><?=word_lang("preview")?></th>
		<th><?=word_lang("title")?></th>
		<th><?=word_lang("size")?></th>
		<th><?=word_lang("delete")?></th>
	</tr>
	<?
	$n=0;
	$tr=1;
	while(!$rs->eof)
	{
		if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
		{
			?>
			<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
			<td class="hidden-phone hidden-tablet"><div  class="photo_preview"><a href="<?=site_root?>/content/galleries/<?=$rs->row["id_parent"]?>/<?=$rs->row["photo"]?>"><img src="<?=site_root?>/content/galleries/<?=$rs->row["id_parent"]?>/thumb<?=$rs->row["id"]?>.jpg" border="0"></a></div></td>
			<td><?=$rs->row["title"]?></td>
			
<td>
		<?
			$img=$_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".(int)$_GET["id"]."/".$rs->row["photo"];
			if(file_exists($img))
			{
				echo(get_exif($img,true));
			}
		?>
		</td>

			<td>	<div class="link_delete"><a href='delete_gallery_photo.php?id=<?=$rs->row["id"]?>&gallery_id=<?=(int)$_GET["id"]?>' onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div></td>
			</tr>
			<?
		}
		$tr++;
		$n++;

		$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
	<?
	echo(paging($n,$str,$kolvo,$kolvo2,"index.php","&d=7&id=".(int)$_GET["id"]));
}
else
{
	echo("<b>".word_lang("not found")."</b>");
}
?>