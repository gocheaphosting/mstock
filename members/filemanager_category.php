<?
if(!isset($_GET["id"]))
{
	$site="upload_category";
}
else
{
	$site="publications";
}
$site2="";
include("../admin/function/db.php");
if(!isset($_SESSION["people_id"])){header("location:login.php");}
if($global_settings["userupload"]==0){header("location:profile_home.php");}
include("../inc/header.php");
include("profile_top.php");
include("../admin/function/upload.php");
?>


<h1>
<?
if(!isset($_GET["id"]))
{
	echo(word_lang("create category"));
}
else
{
	echo(word_lang("edit")." &mdash; ".word_lang("category")." #".$_GET["id"]);
}
?>
</h1>


<script>
	form_fields=new Array('folder','title','description');
	fields_emails=new Array(0,0,0);
	error_message="<?=word_lang("Incorrect field")?>";
</script>
<script src="<?=site_root?>/inc/jquery.qtip-1.0.0-rc3.min.js"></script>

<?
$title="";
$description="";
$keywords="";
$password="";
$foldername="";
$folderid="";
if(isset($_GET["id"]))
{
	$sql="select id_parent,title,description,keywords,userid,password from category where id_parent=".(int)$_GET["id"]." and userid=".(int)$_SESSION["people_id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$title=$rs->row["title"];
		$description=$rs->row["description"];
		$keywords=$rs->row["keywords"];
		$password=$rs->row["password"];

		$sql="select id,id_parent from structure where id=".$rs->row["id_parent"];
		$dr->open($sql);
		if(!$dr->eof)
		{
			$sql="select id,id_parent,name from structure where id=".$dr->row["id_parent"];
			$ds->open($sql);
			if(!$ds->eof)
			{
				$foldername=$ds->row["name"];
				$folderid=$ds->row["id"];
			}
		}
	}
}
?>



<form method="post" Enctype="multipart/form-data" action="upload_category.php?d=1<?if(isset($_GET["id"])){echo("&id=".$_GET["id"]);}?>" name="uploadform"  onSubmit="return my_form_validate();">



<table border="0" cellpadding="0" cellspacing="2" align="right">
<tr height="15">
<td width="20" class="upload_ok">&nbsp;</td>
<td class="smalltext"> - <?=word_lang("upload permitted")?></td>
</tr>
<tr height="15">
<td width="20" class="upload_error">&nbsp;</td>
<td class="smalltext"> - <?=word_lang("upload denied")?></td>
</tr>
</table>



<div name="sparent" id="sparent" class="form_field">
	<span><b><?=word_lang("category")?>:</b></span>
	<select name="folder" id="folder" style="width:300px" class='ibox form-control'>
	<option value=""></option>
	<?
	$itg="";
	$smarty_buildmenu5_id="buildmenu|5|".(int)$folderid."|".$lng;
	if (!$smarty->isCached('buildmenu5.tpl',$smarty_buildmenu5_id))
	{
		$nlimit=0;
		buildmenu5(5,(int)$folderid,2,0);
	}
	$smarty->cache_lifetime = -1;
	$smarty->assign('buildmenu5', $itg);
	$itg=$smarty->fetch('buildmenu5.tpl',$smarty_buildmenu5_id);
	echo($itg);
	?>
	</select>
</div>

<div class="form_field">
	<span><b><?=word_lang("title")?>:</b></span>
	<input class='ibox form-control' name="title" id="title" value="<?=$title?>" type="text" style="width:300px">
</div>

<div  class="form_field">
	<span><b><?=word_lang("description")?>:</b></span>
	<textarea class='ibox form-control' name="description" id="description" style="width:300px;height:70px"><?=$description?></textarea>
</div>

<div  class="form_field">
	<span><b><?=word_lang("keywords")?>:</b></span>
	<textarea class='ibox form-control' name="keywords" id="keywords" style="width:300px;height:70px"><?=$keywords?></textarea>
</div>

<div class="form_field">
	<span><b><?=word_lang("password")?>:</b></span>
	<input class='ibox form-control' name="password" id="password" value="<?=$password?>" type="text" style="width:300px">
</div>

<div class="form_field">
	<span><b><?=word_lang("preview")?>:</b></span>
	<input name="photo" type="file" style="width:300px" class="ibox form-control">
	<span class="smalltext">(*.jpg)</span>
</div>

<div class="form_field">
	<input class='isubmit' value="<?=word_lang("save")?>" type="submit">
</div>

</form>

<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>