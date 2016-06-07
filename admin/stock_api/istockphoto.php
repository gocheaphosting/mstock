<?
//Check access
admin_panel_access("settings_stockapi");

if(!defined("site_root")){exit();}
?>

<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">

<p>
<b>Documentation:</b><br>
<a href="http://developers.gettyimages.com/">http://developers.gettyimages.com</a>
</p>

</div>
<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">

	<form method="post" action="istockphoto_change.php">


	
	<div class='admin_field'>
	<span>Gettyimages API:</span>
	<input type="checkbox" name="istockphoto_api" value="1" <?if($global_settings["istockphoto_api"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span>Gettyimages Key:</span>
	<input type="text" name="istockphoto_id" value="<?=$global_settings["istockphoto_id"]?>" style="width:350px"><br>
	</div>
	
	<div class='admin_field'>
	<span>Gettyimages Secret:</span>
	<input type="text" name="istockphoto_secret" value="<?=$global_settings["istockphoto_secret"]?>" style="width:350px"><br>
	</div>
	
	<div class='admin_field'>
	<span>Gettyimages <?=word_lang("Contributor")?>:</span>
	<input type="text" name="istockphoto_contributor" value="<?=$global_settings["istockphoto_contributor"]?>" style="width:350px"><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("keywords")?> (<?=word_lang("by default")?>):</span>
	<input type="text" name="istockphoto_query" value="<?=$global_settings["istockphoto_query"]?>" style="width:350px">
	<small>It is required. Otherwise the default listing will be empty.</small>
	</div>
	

	
	<div class='admin_field'>
	<span><?=word_lang("Create internal pages for files")?>:</span>
	<input type="checkbox" name="istockphoto_pages" value="1" <?if($global_settings["istockphoto_pages"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span>Affiliate link:</span>
	<input type="text" name="istockphoto_affiliate" value="<?=$global_settings["istockphoto_affiliate"]?>" style="width:350px">
	<small><a href="http://www.gettyimagesaffiliates.com/programs-3/" target="blank">Gettyimages affiliate program</a> uses Linkconnector or Performancehorizon services. First you should register there and get the affiliate link.<br>You can use the syntax in the affiliate link:<br>{URL} - URL of the publication page on Gettyimages or iStockphoto. You redirect visitors to the URL with your aff link.<br>{URL_ENCODED} - encoded publication URL<br>{ID} - publication ID on Gettyimages.</small>
	</div>
	
	<div class='admin_field'>
	<span>Redirect visitors to:</span>
	<select name="istockphoto_site" style="width:350px">
		<option value="gettyimages" <?if($global_settings["istockphoto_site"] == 'gettyimages'){echo("selected");}?>>Gettyimages</option>
		<option value="istockphoto" <?if($global_settings["istockphoto_site"] == 'istockphoto'){echo("selected");}?>>Istockphoto</option>
	</select>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("files")?>:</span>
	<input type="checkbox" name="istockphoto_files" value="1" <?if($global_settings["istockphoto_files"]){echo("checked");}?>><br>
	</div>

	<div class='admin_field'>
	<span><?=word_lang("Prints on Demand")?>:</span>
	<input type="checkbox" name="istockphoto_prints" value="1" <?if($global_settings["istockphoto_prints"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("by default")?>:</span>
	<select name="istockphoto_show" style="width:350px">
		<option value="1" <?if($global_settings["istockphoto_show"] == 1){echo("selected");}?>><?=word_lang("files")?></option>
		<option value="2" <?if($global_settings["istockphoto_show"] == 2){echo("selected");}?>><?=word_lang("prints")?></option>
	</select>
	</div>
	


	
	<div class='admin_field'>
	<input  class="btn btn-primary" type="submit" value="<?=word_lang("save")?>">
	</div>

	</form>

</div>
