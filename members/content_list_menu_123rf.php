<?
if(!defined("site_root")){exit();}
?>


<form id='listing_form' method="get" action="<?=site_root?>/index.php" style="margin:0px">

<div class="search_left_top"></div>
<div class="search_left_body">
	<div class="search_title"><?=word_lang("search")?></div>

	<div class="search_text">
		<div class="search_title2"><b><?=word_lang("keywords")?>:</b></div>
		<div class="search_text2">
			<input type='text' name='search' style="width:175px" class='ibox form-control' value='<?=result(@$_REQUEST["search"])?>'>
		</div>
		
		<?if($global_settings["rf123_contributor"]==""){?>
		<div class="search_title2"><b><?=word_lang("Contributor")?>:</b></div>
		<div class="search_text2">
			<input type='text' name='author' style="width:175px" class='ibox form-control' value='<?if(isset($_REQUEST["author"])){echo(result($_REQUEST["author"]));}?>'>
		</div>
		<?}?>
	
		<div class="search_title2"><b><?=word_lang("type")?>:</b></div>
		<div class="search_text2">
			<select name="stock_type" style="width:175px" class='ibox form-control'>
			<?
			$list_stocktypes['all'] = '';
			$list_stocktypes['0'] = 'photo';
			$list_stocktypes['1'] = 'illustration';
			$list_stocktypes['4'] = 'editorial';
			
			foreach ($list_stocktypes as $key => $value) 
			{
				$sel="";
				if($key==@$_REQUEST["stock_type"] and @$_REQUEST["stock_type"]!='')
				{
					$sel="selected";
				}
				?>
				<option value='<?=$key?>' <?=$sel?>><?=word_lang($value)?></option>
				<?
			}
			?>
			</select>
		</div>	
		
		<div class="search_title2"><b><?=word_lang("categories")?>:</b></div>
		<div class="search_text2">
			<select name="category" style="width:175px" class='ibox form-control'>
				<option value=''></option>
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
					if((isset($_REQUEST["category"]) and $_REQUEST["category"] == $key) or (!isset($_REQUEST["category"]) and $key == $global_settings["rf123_category"]))
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
		


		
		<div class="search_title2 field_license"><b><?=word_lang("languages")?>:</b></div>
		<div class="search_text2 field_license">
			<select name="language" style="width:175px" class='ibox form-control'>
			<?
			$list_language["en"] ="English";
			$list_language["fr"] ="Français";
			$list_language["de"] ="Deutsch";
			$list_language["it"] ="Italiano";	
			$list_language["es"] ="Español";
			$list_language["jp"] ="Japanese";
			$list_language["ru"] ="Russian";
			$list_language["gb"] ="Chinese";

			foreach ($list_language as $key => $value) 
			{
				$sel="";
				if($key=="en" and !isset($_REQUEST["language"]))
				{
					$sel="selected";
				}
				
				if($key==@$_REQUEST["language"])
				{
					$sel="selected";
				}
				?>
				<option value='<?=$key?>' <?=$sel?>><?=$value?></option>
				<?
			}
			?>
			</select>
		</div>	
		
		<div class="search_title2 field_orientation"><b><?=word_lang("orientation")?>:</b></div>
		<div class="search_text2 field_orientation">
			<select name="orientation" style="width:175px" class='ibox form-control'>
			<?
			$list_orientation=array("all", "horizontal", "vertical", "Square");
			foreach ($list_orientation as $key => $value) 
			{
				$sel="";
				if($value==@$_REQUEST["orientation"])
				{
					$sel="selected";
				}
				?>
				<option value='<?=strtolower($value)?>'  <?=$sel?>><?=word_lang($value)?></option>
				<?
			}
			?>
			</select>
		</div>	
		


		<div class="search_title2 field_license"><b><?=word_lang("color")?>:</b></div>
		<div class="search_text2 field_license">
			<select name="color" style="width:175px" class='ibox form-control'>
			<option value=""></option>
			<?
			$list_colors[0] ="000000";
			$list_colors[1] ="996100";
			$list_colors[2] ="636300";
			$list_colors[3] ="006300";
			$list_colors[4] ="006366";
			$list_colors[5] ="000080";
			$list_colors[6] ="636399";
			$list_colors[7] ="636363";
			$list_colors[8] ="800000";
			$list_colors[9] ="ff6600";
			$list_colors[10] ="808000";
			$list_colors[11] ="8000ff";
			$list_colors[12] ="008080";
			$list_colors[13] ="0000ff";
			$list_colors[14] ="666699";
			$list_colors[15] ="808080";
			$list_colors[16] ="ff0000";
			$list_colors[17] ="ff9900";
			$list_colors[18] ="99cc00";
			$list_colors[19] ="639966";
			$list_colors[20] ="63cccc";
			$list_colors[21] ="6366ff";
			$list_colors[22] ="800080";
			$list_colors[23] ="999999";
			$list_colors[24] ="ff00ff";
			$list_colors[25] ="ffcc00";
			$list_colors[26] ="ffff00";
			$list_colors[27] ="00ff00";
			$list_colors[28] ="00ffff";
			$list_colors[29] ="00ccff";
			$list_colors[30] ="996366";
			$list_colors[31] ="c0c0c0";
			$list_colors[32] ="ff99cc";
			$list_colors[33] ="ffcc99";
			$list_colors[34] ="ffff99";
			$list_colors[35] ="ccffcc";
			$list_colors[36] ="ccffff";
			$list_colors[37] ="99ccff";
			$list_colors[38] ="cc99ff";
			


			foreach ($list_colors as $key => $value) 
			{
				$sel="";
				if($key==@$_REQUEST["color"] and @$_REQUEST["color"] != '')
				{
					$sel="selected";
				}
				?>
				<option value='<?=$key?>' <?=$sel?> style='background-color:#<?=$value?>;background:#<?=$value?>'><?=$key?></option>
				<?
			}
			?>
			</select>
		</div>			

		
		
		<div class="search_title2 field_age"><b><?=word_lang("Age")?>:</b></div>
		<div class="search_text2 field_age">
			<select name="age" style="width:175px" class='ibox form-control'>
			<?
			$list_age=array('' , 'Babies' , 'Children' , 'Teenagers' , 'Adults' , 'Seniors');
			foreach ($list_age as $key => $value) 
			{
				$sel="";
				if($key==@$_REQUEST["age"])
				{
					$sel="selected";
				}
				?>
				<option value='<?=$key?>'  <?=$sel?>><?=word_lang($value)?></option>
				<?
			}
			?>
			</select>
		</div>
		
		<div class="search_title2 field_gender"><b><?=word_lang("Gender")?>:</b></div>
		<div class="search_text2 field_gender">
			<select name="gender" style="width:175px" class='ibox form-control'>
			<?
			$list_gender=array("","male", "female");
			foreach ($list_gender as $key => $value) 
			{
				$sel="";
				if($key==@$_REQUEST["gender"])
				{
					$sel="selected";
				}
				?>
				<option value='<?=$key?>'  <?=$sel?>><?=word_lang($value)?></option>
				<?
			}
			?>
			</select>
		</div>
		
		<div class="search_title2 field_ethnicity"><b><?=word_lang("Ethnicity")?>:</b></div>
		<div class="search_text2 field_ethnicity">
			<select name="ethnicity" style="width:175px" class='ibox form-control'>
			<?
			$list_ethnicity=array('','without_people', 'caucasian', 'african_american', 'asian', 'hispanic');
			foreach ($list_ethnicity as $key => $value) 
			{
				$sel="";
				if($value==@$_REQUEST["ethnicity"])
				{
					$sel="selected";
				}
				?>
				<option value='<?=$value?>'  <?=$sel?>><?=word_lang(str_replace("_"," ",$value))?></option>
				<?
			}
			?>
			</select>
		</div>
		
		<div class="search_title2 field_people_number"><b><?=word_lang("People number")?>:</b></div>
		<div class="search_text2 field_people_number">
			<select name="people_number" style="width:175px" class='ibox form-control'>
			<?
			$list_peoplenumber['n'] = '';
			$list_peoplenumber['1'] = '1 person';
			$list_peoplenumber['2'] = '2 people';
			$list_peoplenumber['3'] = '3 people';
			$list_peoplenumber['4'] = '4 people or more';
			
			
			foreach ($list_peoplenumber as $key => $value) 
			{
				$sel="";
				if($key==@$_REQUEST["people_number"])
				{
					$sel="selected";
				}
				?>
				<option value='<?=$key?>'  <?=$sel?>><?=word_lang($value)?></option>
				<?
			}
			?>
			</select>
		</div>
		

	</div>
	
	<div class="search_text"><input type="submit" value="<?=word_lang("search")?>" class="isubmit"></div>
</div>
<div class="search_left_bottom"></div>
</form>

