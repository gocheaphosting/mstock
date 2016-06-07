<?
//Check access
admin_panel_access("settings_stockapi");

if(!defined("site_root")){exit();}
?>

<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">

<p>
<b>Documentation:</b><br>
<a href="http://depositphotos.com/api-program.html">http://depositphotos.com/api-program.html</a>
</p>

</div>
<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">

	<form method="post" action="depositphotos_change.php">


	
	<div class='admin_field'>
	<span>Depositphotos API:</span>
	<input type="checkbox" name="depositphotos_api" value="1" <?if($global_settings["depositphotos_api"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span>Partner API Key:</span>
	<input type="text" name="depositphotos_id" value="<?=$global_settings["depositphotos_id"]?>" style="width:350px"><br>
	</div>
	
	<div class='admin_field'>
	<span>Affiliate URL:</span>
	<input type="text" name="depositphotos_affiliate" value="<?=$global_settings["depositphotos_affiliate"]?>" style="width:650px">
	<small>First you should register on <a href="https://www.depositphotos.com/affiliate.html" target="blank">Depositphotos affiliate program</a>. <br>You can use the syntax in the affiliate link:<br>{URL} - URL of the publication page on Depositphotos. You redirect visitors to the URL with your aff link.<br>{URL_ENCODED} - encoded publication URL<br>{ID} - publication ID on Depositphotos.<br><br>
	<b>Example of correct Affiliate URL:</b> http://tracking.depositphotos.com/aff_c?offer_id=4&aff_id=9914&url={URL_ENCODED}%3Faff%3D9914<br>where 9914 - your affiliate ID.</small>
	</div>
	
	<div class='admin_field'>
	<span>Depositphotos <?=word_lang("Contributor")?>:</span>
	<input type="text" name="depositphotos_contributor" value="<?=$global_settings["depositphotos_contributor"]?>" style="width:350px"><br>
	</div>
	
	<div class='admin_field'>
	<span>Depositphotos <?=word_lang("categories")?>:</span>
	<select name="depositphotos_category" style="width:350px">
		<option value="-1"></option>
		<?
		$sql="select id,title from category_stock where stock='depositphotos' order by title";
		$rs->open($sql);
		while(!$rs->eof)
		{
			$sel="";
			if($rs->row["id"] == $global_settings["depositphotos_category"])
			{
				$sel="selected";
			}
			?>
			<option value="<?=$rs->row["id"]?>" <?=$sel?>><?=$rs->row["title"]?></option>
			<?
			$rs->movenext();
		}
		?>
	</select>
	<a href="depositphotos_categories.php" class="btn btn-default" style="margin-top:2px"><i class="fa fa-refresh"></i> <?=word_lang("refresh")?></a>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("Create internal pages for files")?>:</span>
	<input type="checkbox" name="depositphotos_pages" value="1" <?if($global_settings["depositphotos_pages"]){echo("checked");}?>><br>
	</div>

		<div class='admin_field'>
	<span><?=word_lang("files")?>:</span>
	<input type="checkbox" name="depositphotos_files" value="1" <?if($global_settings["depositphotos_files"]){echo("checked");}?>><br>
	</div>

	<div class='admin_field'>
	<span><?=word_lang("Prints on Demand")?>:</span>
	<input type="checkbox" name="depositphotos_prints" value="1" <?if($global_settings["depositphotos_prints"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("by default")?>:</span>
	<select name="depositphotos_show" style="width:350px">
		<option value="1" <?if($global_settings["depositphotos_show"] == 1){echo("selected");}?>><?=word_lang("files")?></option>
		<option value="2" <?if($global_settings["depositphotos_show"] == 2){echo("selected");}?>><?=word_lang("prints")?></option>
	</select>
	</div>



	
	<div class='admin_field'>
	<input  class="btn btn-primary" type="submit" value="<?=word_lang("save")?>">
	</div>

	</form>

</div>
