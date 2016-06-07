<?if(!defined("site_root")){exit();}?>


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



<div class="form_field"><span><?=word_lang("category")?> 2:</span>
	<select name="folder2" style="width:450px" class='ibox form-control'>
	<option value="0"></option>
	<?
	echo($itg);
	?>
	</select>
</div>




<div class="form_field"><span><?=word_lang("category")?> 3:</span>
	<select name="folder3" style="width:450px" class='ibox form-control'>
	<option value="0"></option>
	<?
	echo($itg);
	?>
	</select>
</div>










<?
if(!$global_settings["printsonly"])
{
?>
<div class="form_field">
	<span><b><?=word_lang("price")?>:</b></span>
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
				<input type="radio" name="license_type"  id="license_type1" value="0" checked onClick="set_license(1)">&nbsp;<label for='license_type1' style='display:inline;font-size:12px'><?=word_lang("royalty free")?></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="license_type"  id="license_type2" value="1" onClick="set_license(2)">&nbsp;<label for='license_type2'  style='display:inline;font-size:12px'><?=word_lang("rights managed")?></label>
			<?
		}
		else
		{
			?>
			<input type="hidden" name="license_type" value="<?if(!$global_settings["royalty_free"]){echo(1);}else{echo(0);}?>">
			<?
		}
	?>
	<?$file_form=false;$flag_jquery=true;?>
	<?if($global_settings["royalty_free"]){?>
	<div id="box_license1" style="display:block">
		<?=photo_upload_form(0,false)?>
	</div>
	<?}?>
	
	<?if($global_settings["rights_managed"] and $global_settings["rights_managed_sellers"]){?>
	<div id="box_license2" style="display:<?if(!$global_settings["royalty_free"]){echo("block");}else{echo("none");}?>">
		<?=rights_managed_upload_form("photo",1,0,false)?>
	</div>
	<?}?>
</div>
<?
}
?>












<?
if($global_settings["prints_users"])
{
?>
<div class="form_field">
	<span><b><?=word_lang("prints and products")?>:</b></span>
<?
echo(prints_upload_form()."</div>");
}
?>












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
				
		$sql="select name,id_parent from models where user='".result($_SESSION["people_login"])."' order by name";
		$dd->open($sql);
		while(!$dd->eof)
		{			
			$model_list.="<li id='model{TYPE}_".$dd->row["id_parent"]."' style='display:block'><a href=\"javascript:model_add(".$dd->row["id_parent"].",{TYPE},'".addslashes($dd->row["name"])."');\">".$dd->row["name"]."</a></li>";
			
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
	<input class='ibox form-control gllpLatitude' name="google_x" value="0" type="text" style="width:200px">
</div>

<div class="form_field">
	<span><b><?=word_lang("Google coordinate Y")?>:</b></span>
	<input class='ibox form-control gllpLongitude' name="google_y" value="0" type="text" style="width:200px">
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
