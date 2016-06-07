<?$site="printslab";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?if($global_settings["userupload"]==0){header("location:profile.php");}?>
<?include("../inc/header.php");?>




<?include("profile_top.php");?>


<h1><?=word_lang("prints lab")?>

<?
if(isset($_GET["id"]))
{
	echo(" &mdash; ".word_lang("edit gallery"));
	$sql="select title from galleries where id=".(int)$_GET["id"]." and user_id=".(int)$_SESSION["people_id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$title=$rs->row["title"];
	}
	else
	{
		exit();
	}
	$com="change";
	$com2="?id=".(int)$_GET["id"];
}
else
{
	echo(" &mdash; ".word_lang("add new gallery"));
	$title="";
	$description="";
	$com="add";
	$com2="";
}
?>
</h1>


<form method="post" Enctype="multipart/form-data" action="printslab_<?=$com?>.php<?=$com2?>">


<div class="form_field">
	<span><b><?=word_lang("title")?>:</b></span>
	<input class='ibox form-control' name="title" type="text" style="width:300px" value="<?=$title?>">
</div>


<?
if(isset($_GET["id"]) and (int)$_GET["id"]>0)
{
	$sql="select * from galleries_photos where id_parent='".(int)$_GET["id"]."' order by data desc";
	$rs->open($sql);
	if(!$rs->eof)
	{
	?>
	<table border="0" cellpadding="0" cellspacing="0" class="profile_table" width="100%" style="margin-top:15px">
	<tr>
	<th><?=word_lang("preview")?></th>	
	<th><?=word_lang("title")?></th>
	<th><?=word_lang("size")?></th>
	<th><?=word_lang("date")?></th>
	<th><?=word_lang("delete")?></th>
	</tr>
	<?
	$tr=1;
	while(!$rs->eof)
	{
		?>
		<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
		<td><div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr2"><img src="<?=site_root?>/content/galleries/<?=$rs->row["id_parent"]?>/thumb<?=$rs->row["id"]?>.jpg"></div></div></div></div></div></div></div></div></td>		
		<td>
			<input type="text" name="title<?=$rs->row["id"]?>" value="<?=$rs->row["title"]?>" style="width:300px">
		</td>
		<td>
		<?
			$img=$_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".(int)$_GET["id"]."/".$rs->row["photo"];
			if(file_exists($img))
			{
				echo(get_exif($img,true));
			}
		?>
		</td>
		<td><div class="link_date"><?=date(date_format,$rs->row["data"])?></div></td>
		<td style="text-align:center"><input type="checkbox" name="delete<?=$rs->row["id"]?>"></td>
		</tr>
		<?
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
	<?
	}
}
?>



<div class="form_field">
	<input class='isubmit' value="<?=word_lang("save")?>" name="subm" type="submit">
</div>

</form>






<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>