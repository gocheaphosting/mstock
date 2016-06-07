<?
if(!isset($_GET["id"]))
{
	$site="upload_vector";
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
	echo(word_lang("upload vector"));
}
else
{
	echo(word_lang("edit")." &mdash; ".word_lang("vector")." #".$_GET["id"]);
}
?>
</h1>

<script>
	form_fields=new Array('folder','title');
	fields_emails=new Array(0,0);
	error_message="<?=word_lang("Incorrect field")?>";
</script>
<script src="<?=site_root?>/inc/jquery.qtip-1.0.0-rc3.min.js"></script>

<?
$title="";
$description="";
$keywords="";
$foldername="";
$folderid="";
$folderid2=0;
$folderid3=0;
$model=0;
$flash_version="";
$script_version="";
$flash_width=$global_settings["flash_width"];
$flash_height=$global_settings["flash_height"];
$free=0;
$pnew=true;
$google_x=0;
$google_y=0;
$adult=0;

if($global_settings["royalty_free"])
{
	$rights_managed=0;
}
else
{
	$rights_managed=1;
}

if(isset($_GET["id"]))
{
	$id=(int)$_GET["id"];
	
	$sql="select id_parent,title,description,keywords,userid,model,flash_width,flash_height,flash_version,script_version,free,category2,category3,google_x,google_y,adult,rights_managed from vector where id_parent=".(int)$_GET["id"]."  and (userid=".(int)$_SESSION["people_id"]." or author='".result($_SESSION["people_login"])."')";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$title=$rs->row["title"];
		$description=$rs->row["description"];
		$keywords=$rs->row["keywords"];
		$model=$rs->row["model"];
		$flash_width=$rs->row["flash_width"];
		$flash_height=$rs->row["flash_height"];
		$flash_version=$rs->row["flash_version"];
		$script_version=$rs->row["script_version"];
		$free=$rs->row["free"];
		$pnew=false;
		$folderid2=(int)$rs->row["category2"];
		$folderid3=(int)$rs->row["category3"];
		$google_x=$rs->row["google_x"];
		$google_y=$rs->row["google_y"];
		$adult=$rs->row["adult"];
		$rights_managed=$rs->row["rights_managed"];

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
	else
	{
		exit();
	}
}
else
{
	$id=0;
}
?>







<form method="post" Enctype="multipart/form-data" action="upload_vector.php?d=5<?if(isset($_GET["id"])){echo("&id=".$_GET["id"]);}?>" id="uploadform" name="uploadform"  onSubmit="return my_form_validate();">


<?
if(isset($_GET["id"]))
{
?>
<div class="subheader"><?=word_lang("preview")?></div>	
<div class="subheader_text">
<?=show_preview((int)$_GET["id"],"vector",2,0)?>
</div>
<?
}
?>





<div class="subheader"><?=word_lang("file for sale")?></div>	
<div class="subheader_text">
<?
		if($global_settings["royalty_free"] and $global_settings["rights_managed"] and $global_settings["rights_managed_sellers"])
		{
			?>
				<script>
					function set_license(value)
					{
						if(value==1)
						{
							document.getElementById('box_license2').style.display='none';
							document.getElementById('box_license1').style.display='block';
						}
						else
						{
							document.getElementById('box_license2').style.display='block';
							document.getElementById('box_license1').style.display='none';
						}
					}
				</script>
				<input type="radio" name="license_type"  id="license_type1" value="0" <?if($rights_managed==0){echo("checked");}?> onClick="set_license(1)">&nbsp;<label for='license_type1' style='display:inline;font-size:12px'><?=word_lang("royalty free")?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="license_type"  id="license_type2" value="1" <?if($rights_managed>0){echo("checked");}?> onClick="set_license(2)">&nbsp;<label for='license_type2'  style='display:inline;font-size:12px'><?=word_lang("rights managed")?></label>
			<?
		}
		else
		{
			?>
			<input type="hidden" name="license_type" value="<?if(!$global_settings["royalty_free"]){echo(1);}else{echo(0);}?>">
			<?
		}
?>


<?$file_form=true;$flag_jquery=false;?>
<?if($global_settings["royalty_free"]){?>
	<div id="box_license1" style="display:<?if($rights_managed==0){echo("block");}else{echo("none");}?>">
		<div class="form_field">
			<?=files_upload_form($id,"vector",false)?>
		</div>
	</div>
	<?}?>
	
	<?if($global_settings["rights_managed"] and $global_settings["rights_managed_sellers"]){?>
	<div id="box_license2" style="display:<?if($rights_managed>0){echo("block");}else{echo("none");}?>">
		<?=rights_managed_upload_form("vector",$rights_managed,$id,false)?>
	</div>
	<?}?>
</div>	


</div>














<div class="subheader"><?=word_lang("settings")?></div>	
<div class="subheader_text">

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
	<span><b><?=word_lang("category")?> 1:</b></span>
	<select name="folder" id="folder" style="width:450px" class='ibox form-control'>
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
	<span><?=word_lang("category")?> 2:</span>
	<select name="folder2" id="folder2" style="width:450px" class='ibox form-control'>
	<option value=""></option>
	<?
	$itg="";
	$smarty_buildmenu5_id="buildmenu|5|".(int)$folderid2."|".$lng;
	if (!$smarty->isCached('buildmenu5.tpl',$smarty_buildmenu5_id))
	{
		$nlimit=0;
		buildmenu5(5,(int)$folderid2,2,0);
	}
	$smarty->cache_lifetime = -1;
	$smarty->assign('buildmenu5', $itg);
	$itg=$smarty->fetch('buildmenu5.tpl',$smarty_buildmenu5_id);
	echo($itg);
	?>
	</select>
</div>



<div  class="form_field">
	<span><?=word_lang("category")?> 3:</span>
	<select name="folder3" id="folder3" style="width:450px" class='ibox form-control'>
	<option value=""></option>
	<?
	$itg="";
	$smarty_buildmenu5_id="buildmenu|5|".(int)$folderid3."|".$lng;
	if (!$smarty->isCached('buildmenu5.tpl',$smarty_buildmenu5_id))
	{
		$nlimit=0;
		buildmenu5(5,(int)$folderid3,2,0);
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
	<input class='ibox form-control' name="title" id="title" value="<?=$title?>" type="text" style="width:450px">
</div>

<div  class="form_field">
	<span><b><?=word_lang("description")?>:</b></span>
	<textarea name="description" id="description" style="width:450px;height:70px" class='ibox form-control'><?=$description?></textarea>
</div>

<div  class="form_field">
	<span><b><?=word_lang("keywords")?>:</b></span>
	<textarea name="keywords" id="keywords" style="width:450px;height:70px" class='ibox form-control'><?=$keywords?></textarea>
	<span class="smalltext">(Example: key1,key2)</span>
</div>



<?if($pnew){?>
	<?if($global_settings["flash"]){?>
		<div  class="form_field">
			<span><b>Flash <?=word_lang("size")?>:</b></span>
			<input name="flash_width" id="flash_width" class="ibox form-control" type="text" style="width:30px" value="<?=$flash_width?>">&nbsp;x&nbsp;<input name="flash_height" type="text" class="ibox form-control" style="width:30px" value="<?=$flash_height?>">
		</div>


		<div  class="form_field">
			<span><b><?=word_lang("flash version")?>:</b></span>
			<input name="flash_version" id="flash_version" class="ibox form-control" type="text" style="width:200px" value="<?=$flash_version?>">
			<span class="smalltext">(Example: mx2004+,8+,cs3+)</span>
		</div>


		<div  class="form_field">
			<span><b><?=word_lang("flash script version")?>:</b></span>
			<input name="script_version" id="script_version" class="ibox form-control" type="text" style="width:200px" value="<?=$script_version?>">
			<span class="smalltext">(Example: as1,as2,as3)</span>
		</div>


		<div  class="form_field">
			<span><b>Flash XML file 1:</b></span>
			<input name="xml1" type="file" style="width:300px">
			<span class="smalltext">(*.xml)</span>
		</div>


		<div class="form_field">
			<span><b>Flash XML file 2:</b></span>
			<input name="xml2" type="file" style="width:300px">
			<span class="smalltext">(*.xml)</span>
		</div>


		<div class="form_field">
			<span><b>Flash XML file 3:</b></span>
			<input name="xml3" type="file" style="width:300px">
			<span class="smalltext">(*.xml)</span>
		</div>

	<?}?>
<?}?>










<?if($global_settings["model"]){?>
	<div class="form_field">
		<span><b><?=word_lang("model property release")?>:</b></span>
		<select name="model" id="model" style="width:200px" class='ibox form-control'>
		<option value="0"></option>
		<?
		$sql="select id_parent,name from models where user='".result($_SESSION["people_login"])."' order by name";
		$ds->open($sql);
		while(!$ds->eof)
		{
			$sel="";
			if($ds->row["id_parent"]==$model){$sel="selected";}
			?>
			<option value="<?=$ds->row["id_parent"]?>" <?=$sel?>><?=$ds->row["name"]?></option>
			<?
			$ds->movenext();
		}
		?>
		</select>&nbsp;&nbsp;&nbsp;<a href="models.php"><?=word_lang("edit")?></a>
	</div>
<?}?>






<div  class="form_field">
	<span><b><?=word_lang("free")?>:</b></span>
	<input name="free" id="free" type="checkbox" <?if($free==1){echo("checked");}?>>
</div>

<?if($global_settings["adult_content"]){?>
<div class="form_field">
	<span><b><?=word_lang("adult content")?>:</b></span>
	<input name="adult" type="checkbox" <?if($adult==1){echo("checked");}?>>
</div>
<?}?>


<?if($global_settings["google_coordinates"]){?>
<div  class="gllpLatlonPicker">
	<div class="form_field">
		<span><b><?=word_lang("Google coordinate X")?>:</b></span>
		<input class='ibox form-control gllpLatitude' name="google_x" value="<?=$google_x?>" type="text" style="width:200px">
	</div>

	<div class="form_field">
		<span><b><?=word_lang("Google coordinate Y")?>:</b></span>
		<input class='ibox form-control gllpLongitude' name="google_y" value="<?=$google_y?>" type="text" style="width:200px">
	</div>
	
	<div class="form_field">
	<input type="hidden" class="gllpZoom" value="3"/>
	<input type="hidden" class="gllpUpdateButton" value="update map">
	<div class="gllpMap" id='map' style="width: 500px; height: 250px;margin-bottom:10px"></div>
	<input type="text" class="gllpSearchField ibox form-control" style="width:200px;display:inline">
	<input type="button" class="gllpSearchButton btn btn-default" value="<?=word_lang("search")?>">
	<script src='https://maps.googleapis.com/maps/api/js?sensor=false'></script>
	<script src='<?=site_root?>/inc/js/gmap_picker/jquery-gmaps-latlon-picker.js'></script>
	</div>
</div>	
<?}?>


</div>


<?if(!isset($_GET["id"])){?>
<div class="subheader"><?=word_lang("terms and conditions")?></div>	
<div class="subheader_text">
	<div class="form_field">
		<iframe src="<?=site_root?>/members/agreement.php?type=terms" frameborder="no" scrolling="yes" class="framestyle_terms"></iframe><br>
		<input name="terms" type="radio" value="1" onClick="document.uploadform.subm.disabled=false;"> <?=word_lang("i agree")?>
	</div>
</div>
<?}?>



<div  class="form_field">
	<input class='isubmit' value="<?if(isset($_GET["id"])){echo(word_lang("change"));}else{echo(word_lang("upload"));}?>" name="subm" type="submit" <?if(!isset($_GET["id"])){?>disabled<?}?>>
</div>

</form>


<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>