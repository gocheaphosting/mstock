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


$sql="select id_parent,title,description,photo,published,userid from category where userid>0 order by id_parent desc";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="5" cellspacing="0" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
		<th class="hidden-phone hidden-tablet"><?=word_lang("preview")?></th>
		<th><?=word_lang("title")?></th>
		<th class="hidden-phone hidden-tablet"><?=word_lang("description")?></th>
		<th><?=word_lang("status")?></th>
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
			$preview="../../images/not_found.gif";
			if($rs->row["photo"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["photo"]))
			{
				$preview=$rs->row["photo"];
			}

			$url="../categories/content.php?id=".$rs->row["id_parent"];


			?>
			<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
			<td class="hidden-phone hidden-tablet"><?if($preview!=""){?><a href="<?=$url?>"><img src="<?=$preview?>" border="0"></a><?}?></td>
			<td><a href="<?=$url?>"><?=$rs->row["title"]?></a></td>
			<td class="hidden-phone hidden-tablet"><?=$rs->row["description"]?></td>
			<td><div id="status<?=$rs->row["id_parent"]?>" name="status<?=$rs->row["id_parent"]?>">


			<a href="javascript:cstatus(<?=$rs->row ["id_parent"]?>,1);" <?if($rs->row["published"]!=1){?>class="gray"<?}?>><?=word_lang("approved")?></a><br>
			<a href="javascript:cstatus(<?=$rs->row ["id_parent"]?>,0);" <?if($rs->row["published"]!=0){?>class="gray"<?}?>><?=word_lang("pending")?></a><br>
			<a href="javascript:cstatus(<?=$rs->row ["id_parent"]?>,2);" <?if($rs->row["published"]!=2){?>class="gray"<?}?>><?=word_lang("declined")?></a>



			</div>
			</td>

			<td><div class="link_user"><?
			$sql="select id_parent,login from users where id_parent=".$rs->row["userid"];
			$ds->open($sql);
			if(!$ds->eof)
			{
				?>
				<a href="../customers/content.php?id=<?=$ds->row["id_parent"]?>"><?=$ds->row["login"]?></a>
				<?
			}
			?></div></td>

			<td class="hidden-phone hidden-tablet"><div class="link_edit"><a href='<?=$url?>'><?=word_lang("edit")?></a></div></td>
			<td>	<div class="link_delete"><a href='delete_category.php?id=<?=$rs->row["id_parent"]?>' onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div></td>
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
	echo(paging($n,$str,$kolvo,$kolvo2,"index.php","&d=1"));
}
else
{
	echo("<b>".word_lang("not found")."</b>");
}
?>