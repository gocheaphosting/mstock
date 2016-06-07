<?
$site="publications";$site2="";
include("../admin/function/db.php");
if(!isset($_SESSION["people_id"])){header("location:login.php");}
if($global_settings["userupload"]==0){header("location:profile_home.php");}
include("../inc/header.php");
include("profile_top.php");
include("../admin/function/upload.php");


if($global_settings["userupload"]==0){exit;}
?>





<h1><?=word_lang("edit")?> &mdash; <?=word_lang("photo")?> #<?=@$_GET["id"]?></h1>


<?
$title="";
$description="";
$keywords="";
$foldername="";
$folderid="";
$folderid2=0;
$folderid3=0;
$model=0;
$free=0;
$editorial=0;
$google_x=0;
$google_y=0;
$adult=0;
$rights_managed=0;
$pnew=true;
if(isset($_GET["id"]))
{
	$sql="select id_parent,title,description,keywords,userid,model,free,category2,category3,google_x,google_y,editorial,adult,rights_managed from photos where id_parent=".(int)$_GET["id"]."  and (userid=".(int)$_SESSION["people_id"]." or author='".result($_SESSION["people_login"])."')";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$title=$rs->row["title"];
		$description=$rs->row["description"];
		$keywords=$rs->row["keywords"];
		$model=$rs->row["model"];
		$free=$rs->row["free"];
		$folderid2=(int)$rs->row["category2"];
		$folderid3=(int)$rs->row["category3"];
		$pnew=false;
		$google_x=$rs->row["google_x"];
		$google_y=$rs->row["google_y"];
		$free=$rs->row["free"];
		$editorial=$rs->row["editorial"];
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


$flag_jquery=false;
?>




<form method="post" Enctype="multipart/form-data" action="upload_photo.php?d=2<?if(isset($_GET["id"])){echo("&id=".$_GET["id"]);}?>" name="uploadform" id="uploadform">


<div class="subheader"><?=word_lang("preview")?></div>	
<div class="subheader_text">
<?
if(isset($_GET["id"]))
{
?>
<?=show_preview((int)$_GET["id"],"photo",2,0)?>
<?
}
?>
</div>


<?
if(!$global_settings["printsonly"])
{
?>
<div class="subheader"><?=word_lang("file for sale")?></div>	
<div class="subheader_text">
<div  class="form_field">
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
	<?$file_form=true;?>
	<?if($global_settings["royalty_free"]){?>
	<div id="box_license1" style="display:<?if($rights_managed==0){echo("block");}else{echo("none");}?>">
		<?=photo_upload_form((int)$_GET["id"],false)?>
	</div>
	<?}?>
	
	<?if($global_settings["rights_managed"] and $global_settings["rights_managed_sellers"]){?>
	<div id="box_license2" style="display:<?if($rights_managed>0){echo("block");}else{echo("none");}?>">
		<?=rights_managed_upload_form("photo",$rights_managed,(int)$_GET["id"],false)?>
	</div>
	<?}?>
</div>
</div>
<?
}
?>


<?
if($global_settings["prints_users"])
{
	?>
	<div class="subheader"><?=word_lang("prints and products")?></div>	
	<div class="subheader_text">
	<div class="form_field">
	<?
	if(isset($_GET["id"]))
	{
		echo(prints_live((int)$_GET["id"]));
	}
	?>
	</div>
	</div>
	<?
}
?>


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
<tr>
<td colspan="2" style="padding-top:10px">


</td>
</tr>
</table>




<div name="sparent" id="sparent" class="form_field">
	<span><b><?=word_lang("category")?> 1:</b></span>
	<select name="folder" style="width:450px" class='ibox form-control'>
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







<div  class="form_field">
	<span><?=word_lang("category")?> 2:</span>
	<select name="folder2" style="width:450px" class='ibox form-control'>
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
	<select name="folder3" style="width:450px" class='ibox form-control'>
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






<div  class="form_field">
	<span><b><?=word_lang("title")?>:</b></span>
	<input class='ibox form-control' name="title" value="<?=$title?>" type="text" style="width:450px">
</div>

<div  class="form_field">
	<span><b><?=word_lang("description")?>:</b></span>
	<textarea name="description" style="width:450px;height:70px" class='ibox form-control'><?=$description?></textarea>
</div>

<div  class="form_field">
	<span><b><?=word_lang("keywords")?>:</b></span>
	<textarea name="keywords" style="width:450px;height:70px" class='ibox form-control'><?=$keywords?></textarea><span class="smalltext">(Example: key1,key2)</span>
</div>











<?if($global_settings["model"]){?>

<script>

function model_add(model_id,type,model_name)
{	
	if(type==0)
	{
		type_name="<?=word_lang("Model release")?>";
	}
	else
	{
		type_name="<?=word_lang("Property release")?>";
	}
	
	$('#models_list').append("<div  style='clear:both' id='div_"+model_id+"'><div class='input-append' style='float:left;margin-right:4px'><a href='models_content.php?id="+model_id+"' class='btn btn-small'><b>"+type_name+":</b> "+model_name+"</a></div><button class='btn btn-danger btn-small' type='button' onClick=\"model_delete('"+model_id+"');\"><?=word_lang("delete")?></button><input type='hidden' name='model"+model_id+"' value='"+type+"'></div>");
	
	document.getElementById('model0_'+model_id.toString()).style.display='none';
	document.getElementById('model1_'+model_id.toString()).style.display='none';
}

function model_delete(model_id)
{
	$('#div_'+model_id.toString()).remove()
	document.getElementById('model0_'+model_id.toString()).style.display='block';
	document.getElementById('model1_'+model_id.toString()).style.display='block';
}

</script>

<div class="form_field">
	<span><b><?=word_lang("model property release")?>:</b></span>

<?
		$model_list="";
		$model_ids=array();
		
		$form_result="";
		
		$sql="select publication_id,model_id,models from models_files where publication_id=".(int)$_GET["id"];
		$dn->open($sql);
		while(!$dn->eof)
		{
			$sql="select name from models where id_parent=".$dn->row["model_id"];
			$dd->open($sql);
			if(!$dd->eof)
			{
				$form_result.="<div style='clear:both' id='div_".$dn->row["model_id"]."'><div class='input-append' style='float:left;margin-right:4px'><a href='../models/content.php?id=".$dn->row["model_id"]."' class='btn btn-small'>";
				
				if($dn->row["models"]==0)
				{
					$form_result.="<b>".word_lang("Model release").":</b> ";
				}
				else
				{
					$form_result.="<b>".word_lang("Property release").":</b> ";
				}
				
				$form_result.=$dd->row["name"]."</a>";
				
				$form_result.="</div><button class='btn btn-danger btn-small' type='button' onClick=\"model_delete('".$dn->row["model_id"]."');\">".word_lang("delete")."</button><input type='hidden' name='model".$dn->row["model_id"]."' value='".$dn->row["models"]."'></div>";
				
				$model_ids[$dn->row["model_id"]]=1;
			}
			
			$dn->movenext();
		}
				
		$sql="select name,id_parent from models where user='".result($_SESSION["people_login"])."' order by name";
		$dd->open($sql);
		while(!$dd->eof)
		{			
			if(isset($model_ids[$dd->row["id_parent"]]))
			{
				$model_style="style='display:none'";
			}
			else
			{
				$model_style="style='display:block'";
			}
			
			$model_list.="<li id='model{TYPE}_".$dd->row["id_parent"]."' ".$model_style."><a href=\"javascript:model_add(".$dd->row["id_parent"].",{TYPE},'".addslashes($dd->row["name"])."');\">".$dd->row["name"]."</a></li>";
			
			$dd->movenext();
		}
		
		
		$form_result.="<div id='models_list'></div><div style='clear:both'></div><div class='btn-group'>
    			<a class='btn btn-small' href='#'><i class='icon-plus-sign'></i> ".word_lang("Model release")."</a>
    			<a class='btn dropdown-toggle btn-small' data-toggle='dropdown' href='#'><span class='caret' style='margin:8px 2px 8px 2px'></span></a>
    			<ul class='dropdown-menu' style='width:250px'>
    				".str_replace("{TYPE}","0",$model_list)."
   				</ul>
    		</div><div class='btn-group'>
    			<a class='btn btn-small' href='#'><i class='icon-plus-sign'></i> ".word_lang("Property release")."</a>
    			<a class='btn dropdown-toggle btn-small' data-toggle='dropdown' href='#'><span class='caret' style='margin:8px 2px 8px 2px'></span></a>
    			<ul class='dropdown-menu' style='width:250px'>
    				".str_replace("{TYPE}","1",$model_list)."
   				</ul>
    		</div>";
    		
    	echo($form_result);
?>

</div>
<?}?>




<?
if(!$global_settings["printsonly"])
{
?>
<div class="form_field">
	<span><b><?=word_lang("free")?>:</b></span> 
	<input name="free" type="checkbox" <?if($free==1){echo("checked");}?>>
</div>
<?}?>

<div class="form_field">
	<span><b><?=word_lang("editorial")?>:</b></span> 
	<input name="editorial" type="checkbox" <?if($editorial==1){echo("checked");}?>>
</div>

<?
if($global_settings["adult_content"])
{
?>
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


<div class="form_field">
	<input class='isubmit' value="<?=word_lang("change")?>" name="subm" type="submit" <?if(!isset($_GET["id"])){?>disabled<?}?>>
</div>

</form>


<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>