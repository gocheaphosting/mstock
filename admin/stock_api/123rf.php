<?
//Check access
admin_panel_access("settings_stockapi");

if(!defined("site_root")){exit();}
?>

<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">

<p>
<b>Documentation:</b><br>
<a href="http://www.123rf.com/api">http://www.123rf.com/api</a>
</p>


</div>
<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">

	<form method="post" action="123rf_change.php">


	
	<div class='admin_field'>
	<span>123rf API:</span>
	<input type="checkbox" name="rf123_api" value="1" <?if($global_settings["rf123_api"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span>Non-commercial Key:</span>
	<input type="text" name="rf123_id" value="<?=$global_settings["rf123_id"]?>" style="width:350px"><br>
	</div>
	
	<div class='admin_field'>
	<span>Secret key:</span>
	<input type="text" name="rf123_secret" value="<?=$global_settings["rf123_secret"]?>" style="width:350px"><br>
	</div>
	
		<div class='admin_field'>
	<span>Affiliate URL:</span>
	<input type="text" name="rf123_affiliate" value="<?=$global_settings["rf123_affiliate"]?>" style="width:650px">
	<small>You can use the syntax in the affiliate link:<br>{URL} - URL of the publication page on bigstockphoto. You redirect visitors to the URL with your affiliate link.<br>{URL_ENCODED} - encoded publication URL<br>{ID} - publication ID on 123RF.<br><br><b>Example of correct Affiliate URL:</b> {URL}#your_affiliate_name</small>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("keywords")?> (<?=word_lang("by default")?>):</span>
	<input type="text" name="rf123_query" value="<?=$global_settings["rf123_query"]?>" style="width:350px">
	<small>It is required. Otherwise the default listing will be empty.</small>
	</div>
	
	<div class='admin_field'>
	<span>123rf <?=word_lang("Contributor")?>:</span>
	<input type="text" name="rf123_contributor" value="<?=$global_settings["rf123_contributor"]?>" style="width:350px"><br>
	</div>
	
	<div class='admin_field'>
	<span>123rf <?=word_lang("categories")?>:</span>
	<select name="rf123_category" style="width:350px">
		<option value="-1"></option>
		<?
		$rf123_categories[1] = "Animals & Pets";
		$rf123_categories[2] = "Arts & Architecture";
		$rf123_categories[3] = "Celebrations & Holidays";
		$rf123_categories[4] = "Babies & Kids";
		$rf123_categories[5] = "Background & Graphics";
		$rf123_categories[6] = "Business - Man";
		$rf123_categories[7] = "Business - Woman";
		$rf123_categories[8] = "Concepts & Stills";
		$rf123_categories[9] = "Couples & Families";
		$rf123_categories[10] = "Fruits, Food & Drinks";
		$rf123_categories[11] = "Beauty";
		$rf123_categories[12] = "Illustrations";
		$rf123_categories[13] = "Seniors";
		$rf123_categories[14] = "Nature";
		$rf123_categories[15] = "People - Lifestyle";
		$rf123_categories[16] = "Science & Technology";
		$rf123_categories[17] = "Sports & Leisure";
		$rf123_categories[18] = "Transportation & Industry";
		$rf123_categories[19] = "Landscapes & Travel";
		$rf123_categories[20] = "Health & Medical";
		$rf123_categories[21] = "Education";
		$rf123_categories[22] = "Teenagers";
		$rf123_categories[23] = "Weddings & Matrimony";
		$rf123_categories[24] = "Feelings & Emotions";
		$rf123_categories[25] = "Fitness & Wellness";
		$rf123_categories[26] = "Home Improvement";
		$rf123_categories[27] = "Pregnancy & Maternity";
		$rf123_categories[28] = "Dating & Romance";
		$rf123_categories[29] = "Mobile & Telecommunications";
		$rf123_categories[30] = "Objects & Ornament";
		$rf123_categories[31] = "Business - Concept";
		$rf123_categories[32] = "Business - People";
		foreach ($rf123_categories as $key => $value) 
		{
			$sel="";
			if($key==$global_settings["rf123_category"])
			{
				$sel="selected";
			}
			?>
			<option value='<?=$key?>'  <?=$sel?>><?=$value?></option>
			<?
		}
		?>
	</select>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("Create internal pages for files")?>:</span>
	<input type="checkbox" name="rf123_pages" value="1" <?if($global_settings["rf123_pages"]){echo("checked");}?>><br>
	</div>

	
	<div class='admin_field'>
	<span><?=word_lang("files")?>:</span>
	<input type="checkbox" name="rf123_files" value="1" <?if($global_settings["rf123_files"]){echo("checked");}?>><br>
	</div>

	<div class='admin_field'>
	<span><?=word_lang("Prints on Demand")?>:</span>
	<input type="checkbox" name="rf123_prints" value="1" <?if($global_settings["rf123_prints"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("by default")?>:</span>
	<select name="rf123_show" style="width:350px">
		<option value="1" <?if($global_settings["rf123_show"] == 1){echo("selected");}?>><?=word_lang("files")?></option>
		<option value="2" <?if($global_settings["rf123_show"] == 2){echo("selected");}?>><?=word_lang("prints")?></option>
	</select>
	</div>
	


	
	<div class='admin_field'>
	<input  class="btn btn-primary" type="submit" value="<?=word_lang("save")?>">
	</div>

	</form>


</div>
