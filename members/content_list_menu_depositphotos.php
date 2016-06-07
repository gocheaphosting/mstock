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
		
		<?if($global_settings["depositphotos_contributor"]==""){?>
		<div class="search_title2"><b><?=word_lang("Contributor")?>:</b></div>
		<div class="search_text2">
			<input type='text' name='author' style="width:175px" class='ibox form-control' value='<?if(isset($_REQUEST["author"])){echo(result($_REQUEST["author"]));}?>'>
		</div>
		<?}?>
	
		<div class="search_title2"><b><?=word_lang("type")?>:</b></div>
		<div class="search_text2">
			<select name="stock_type" style="width:175px" class='ibox form-control'>
			<?
			$list_stocktypes=array("","photo","vector","videos");
			foreach ($list_stocktypes as $key => $value) 
			{
				$sel="";
				if($value==@$_REQUEST["stock_type"])
				{
					$sel="selected";
				}
				?>
				<option value='<?=$value?>' <?=$sel?>><?=word_lang($value)?></option>
				<?
			}
			?>
			</select>
		</div>	
		
		<div class="search_title2"><b><?=word_lang("categories")?>:</b></div>
		<div class="search_text2">
			<select name="category" style="width:175px" class='ibox form-control'>
				<option value='-1'></option>
				<?
				$sql="select id,title from category_stock where stock='depositphotos' order by title";
				$rs->open($sql);
				while(!$rs->eof)
				{
					$sel="";
					if((isset($_REQUEST["category"]) and $_REQUEST["category"] == $rs->row["id"]) or (!isset($_REQUEST["category"]) and $rs->row["id"] == $global_settings["depositphotos_category"]))
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
		</div>	
		
		<div class="search_title2 field_license"><b><?=word_lang("license")?>:</b></div>
		<div class="search_text2 field_license">
			<select name="license" style="width:175px" class='ibox form-control'>
			<?
			$list_license=array("", "commercial", "editorial");
			foreach ($list_license as $key => $value) 
			{
				$sel="";
				if($value==@$_REQUEST["license"])
				{
					$sel="selected";
				}
				?>
				<option value='<?=$value?>' <?=$sel?>><?=word_lang($value)?></option>
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
			$list_language["de"] ="Deutsch";
			$list_language["fr"] ="Français";
			$list_language["es"] ="Español";
			$list_language["ru"] ="Русский";
			$list_language["it"] ="Italiano";
			$list_language["pt"] ="Português";
			$list_language["pl"] ="Polski";
			$list_language["nl"] ="Nederlands";
			$list_language["jp"] ="日本語";
			$list_language["cz"] ="Česky";
			$list_language["se"] ="Svenska";
			$list_language["cn"] ="中文";
			$list_language["tr"] ="Türkçe";
			$list_language["mx"] ="Español (Mexico)";
			$list_language["gr"] ="Ελληνικά";
			$list_language["ko"] ="한국어";
			$list_language["br"] ="Português (Brasil)";
			$list_language["hu"] ="Magyar";
			$list_language["ua"] ="Українська";

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
			$list_orientation=array("", "horizontal", "vertical", "Square");
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
		
		<div class="search_title2 field_color"><b><?=word_lang("color")?>:</b></div>
		<div class="search_text2 field_color">
		<link rel='stylesheet' href='<?=site_root?>/inc/js/colorpicker/css/colorpicker.css' type='text/css' />
		<script type='text/javascript' src='<?=site_root?>/inc/js/colorpicker/js/colorpicker.js'></script>
		<script type='text/javascript' src='<?=site_root?>/inc/js/colorpicker/js/eye.js'></script>
		<script type='text/javascript' src='<?=site_root?>/inc/js/colorpicker/js/utils.js'></script>
		<?
			if(!isset($_REQUEST["color"]))
			{
				$_REQUEST["color"]="FFFFFF";
			}
		?>
		<input type='hidden' id='color' name='color' value='<?=$_REQUEST["color"]?>' />
		<div id="customWidget" style="margin-left:-4px">
					<div id="colorSelector2"><div style="background-color: #<?=$_REQUEST["color"]?>"></div></div>
	                <div id="colorpickerHolder2">
	                </div>
				</div>
				
				<script>$('#colorSelector2').ColorPicker({
	color: '#<?=$_REQUEST["color"]?>',
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr,hex) {
		$(colpkr).fadeOut(500);
		return false;		
	},
	onChange: function (hsb, hex, rgb) {
		$('#colorSelector2 div').css('backgroundColor', '#' + hex);
		$('#color').val(hex);
	}
});</script>
		</div>	
		

		
		
		<div class="search_title2 field_age"><b><?=word_lang("Age")?>:</b></div>
		<div class="search_text2 field_age">
			<select name="age" style="width:175px" class='ibox form-control'>
			<?
			$list_age=array('' , 'infant' , 'child' , 'teenager' , '20' , '30' , '40' , '50' , '60' , '70');
			foreach ($list_age as $key => $value) 
			{
				$sel="";
				if($value==@$_REQUEST["age"])
				{
					$sel="selected";
				}
				?>
				<option value='<?=$value?>'  <?=$sel?>><?=word_lang($value)?></option>
				<?
			}
			?>
			</select>
		</div>
		
		<div class="search_title2 field_gender"><b><?=word_lang("Gender")?>:</b></div>
		<div class="search_text2 field_gender">
			<select name="gender" style="width:175px" class='ibox form-control'>
			<?
			$list_gender=array("","male", "female","both");
			foreach ($list_gender as $key => $value) 
			{
				$sel="";
				if($value==@$_REQUEST["gender"])
				{
					$sel="selected";
				}
				?>
				<option value='<?=$value?>'  <?=$sel?>><?=word_lang($value)?></option>
				<?
			}
			?>
			</select>
		</div>
		
		<div class="search_title2 field_ethnicity"><b><?=word_lang("Ethnicity")?>:</b></div>
		<div class="search_text2 field_ethnicity">
			<select name="ethnicity" style="width:175px" class='ibox form-control'>
			<?
			$list_ethnicity=array('','asian' , 'brazilian' , 'black' , 'caucasian' , 'hispanic' , 'middle' , 'multi' , 'native' , 'other');
			foreach ($list_ethnicity as $key => $value) 
			{
				$sel="";
				if($value==@$_REQUEST["ethnicity"])
				{
					$sel="selected";
				}
				?>
				<option value='<?=$value?>'  <?=$sel?>><?=word_lang($value)?></option>
				<?
			}
			?>
			</select>
		</div>
		
		<div class="search_title2 field_people_number"><b><?=word_lang("People number")?>:</b></div>
		<div class="search_text2 field_people_number">
			<select name="people_number" style="width:175px" class='ibox form-control'>
			<?
			$list_peoplenumber=array("","0","1","2","3");
			foreach ($list_peoplenumber as $key => $value) 
			{
				$sel="";
				if($value==@$_REQUEST["people_number"])
				{
					$sel="selected";
				}
				?>
				<option value='<?=$value?>'  <?=$sel?>><?=word_lang($value)?></option>
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

