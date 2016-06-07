<?$site="models";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?if($global_settings["userupload"]==0){header("location:profile.php");}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>


<input type="button" value="<?=word_lang("add")?> <?=word_lang("model property release")?>" class="profile_button" onClick="location.href='models_new.php'">

<h1><?=word_lang("models")?></h1>






<?
$sql="select * from models where user='".result($_SESSION["people_login"])."' order by name";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<table border="0" cellpadding="0" cellspacing="0" class="profile_table" width="100%" style="margin-top:15px">
	<tr>
	<th class='hidden-phone hidden-tablet'><?=word_lang("photo")?></th>
	<th><?=word_lang("title")?></th>
	<th><?=word_lang("edit")?></th>
	<th><?=word_lang("delete")?></th>
	</tr>
	<?
	$tr=1;
	while(!$rs->eof)
	{
		?>
		<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
		<td class='hidden-phone hidden-tablet'>
		<?
			$photo=site_root."/images/e.gif";
			if(file_exists($_SERVER["DOCUMENT_ROOT"].$rs->row["modelphoto"])){$photo=$rs->row["modelphoto"];}
		?>
		<a href="<?=model_url($rs->row["id_parent"])?>"><img src="<?=$photo?>" border="0"></a>
		</td>
		<td><a href="models_content.php?id=<?=$rs->row["id_parent"]?>"><?=$rs->row["name"]?></a></td>
		<td><div class="link_edit"><a href="models_content.php?id=<?=$rs->row["id_parent"]?>"><?=word_lang("edit")?></a></div></td>
		<td><div class="link_delete"><a href="models_delete.php?id=<?=$rs->row["id_parent"]?>" onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div></td>
		</tr>
		<?
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
<?
}
else
{
?>
	<b><?=word_lang("not found")?></b>
<?
}
?>


<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>