<?
if(!defined("site_root")){exit();}
?>

<script>
	fields_photo = new Array("license","languages","orientation","model","language","color");
	fields_video = new Array("aspect_ratio","duration_video","language","resolution","model");

	function change_stock_type(value)
	{
		if(value == '' || value == 'photo' || value == 'illustration')
		{		
			deactivate_fields('video');
			activate_fields('photo');
		}
		
		if(value == 'videos')
		{
			deactivate_fields('photo');
			activate_fields('video');
		}
	}
	
	function activate_fields(value)
	{
		if(value == 'photo')
		{
			fields_stock = fields_photo;
		}
		if(value == 'video')
		{
			fields_stock = fields_video;
		}
		
		for(i=0;i<fields_stock.length;i++)
		{
			$('.field_' + fields_stock[i]).css('display','block');
		}
	}
	
	function deactivate_fields(value)
	{
		if(value == 'photo')
		{
			fields_stock = fields_photo;
		}
		if(value == 'video')
		{
			fields_stock = fields_video;
		}
		
		for(i=0;i<fields_stock.length;i++)
		{
			$('.field_' + fields_stock[i]).css('display','none');
		}
	}
</script>

<form id='listing_form' method="get" action="<?=site_root?>/index.php" style="margin:0px">

<div class="search_left_top"></div>
<div class="search_left_body">
	<div class="search_title"><?=word_lang("search")?></div>

	<div class="search_text">
		<div class="search_title2"><b><?=word_lang("keywords")?>:</b></div>
		<div class="search_text2">
			<input type='text' name='search' style="width:175px" class='ibox form-control' value='<?=result(@$_REQUEST["search"])?>'>
		</div>
		
		<?if($global_settings["fotolia_contributor"]==""){?>
		<div class="search_title2"><b><?=word_lang("Contributor")?> ID:</b></div>
		<div class="search_text2">
			<input type='text' name='author' style="width:175px" class='ibox form-control' value='<?if(isset($_REQUEST["author"])){echo(result($_REQUEST["author"]));}?>'>
		</div>
		<?}?>
	
		<div class="search_title2"><b><?=word_lang("type")?>:</b></div>
		<div class="search_text2">
			<select name="stock_type" style="width:175px" class='ibox form-control' onChange='change_stock_type(this.value)'>
			<?
			$list_stocktypes=array("","photo","illustration","vector","videos");
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
				$sql="select id,title from category_stock where stock='fotolia' order by title";
				$rs->open($sql);
				while(!$rs->eof)
				{
					$sel="";
					if((isset($_REQUEST["category"]) and $_REQUEST["category"] == $rs->row["id"]) or (!isset($_REQUEST["category"]) and $rs->row["id"] == $global_settings["fotolia_category"]))
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
		
		<div class="search_title2 field_license"><b><?=word_lang("size")?>:</b></div>
		<div class="search_text2 field_license">
			<select name="license" style="width:175px" class='ibox form-control'>
			<?
			$list_license=array("","L", "XL", "XXL", "XXL>25MP");
			foreach ($list_license as $key => $value) 
			{
				$sel="";
				if($value==@$_REQUEST["license"])
				{
					$sel="selected";
				}
				?>
				<option value='<?=$value?>' <?=$sel?>><?=$value?></option>
				<?
			}
			?>
			</select>
		</div>	
		

		
		<div class="search_title2 field_license"><b><?=word_lang("languages")?>:</b></div>
		<div class="search_text2 field_license">
			<select name="language" style="width:175px" class='ibox form-control'>
			<?
			$lang_fotolia[2] = "American";
			$lang_fotolia[1] = "French";			
			$lang_fotolia[3] = "English";
			$lang_fotolia[4] = "German";
			$lang_fotolia[5] = "Spanish";
			$lang_fotolia[6] = "Italian";
			$lang_fotolia[7] = "Portuguese";
			$lang_fotolia[8] = "Brazilian";
			$lang_fotolia[9] = "Japanese";
			$lang_fotolia[11] = "Polish";
			$lang_fotolia[12] = "Russian";
			$lang_fotolia[13] = "Chinese";
			$lang_fotolia[14] = "Turkish";
			$lang_fotolia[15] = "Korean";
			$lang_fotolia[22] = "Dutch";
			$lang_fotolia[23] = "Swedish";
			
			foreach ($lang_fotolia as $key => $value) 
			{
				$sel="";
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
			$list_orientation=array("", "horizontal", "vertical");
			foreach ($list_orientation as $key => $value) 
			{
				$sel="";
				if($value==@$_REQUEST["orientation"])
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
		
		<div class="search_title2 field_model"><b><?=word_lang("model property release")?>:</b></div>
			
		<div class="search_text2 field_model">
			<input type="checkbox" name="model" <?if(@$_REQUEST["model"]==1){echo("checked");}?> value="1">
		</div>	
		
		
		<div class="search_title2 field_aspect_ratio"><b><?=word_lang("duration")?>:</b></div>
		<div class="search_text2 field_aspect_ratio">
			<select name="duration" style="width:175px" class='ibox form-control'>
			<?
			$list_duration["all"]="";
			$list_duration[""]="0 - 10 sec.";
			$list_duration["10"]="10 - 20 sec.";
			$list_duration["20"]="20 - 30 sec.";
			$list_duration["30"]=" > 30 sec.";

			foreach ($list_duration as $key => $value) 
			{
				$sel="";
				if($key==@$_REQUEST["duration"])
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
		
		<div class="search_title2 field_resolution"><b><?=word_lang("resolution")?>:</b></div>
		<div class="search_text2 field_resolution">
			<select name="resolution" style="width:175px" class='ibox form-control'>
			<?
			$list_resolution[""]="";
			$list_resolution["HD1080"]="HD1080";
			$list_resolution["HD720"]="HD720";

			foreach ($list_resolution as $key => $value) 
			{
				$sel="";
				if($key==@$_REQUEST["resolution"])
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

<?
if(@$_REQUEST["stock_type"]!="")
{
	?><script>change_stock_type('<?=result(@$_REQUEST["stock_type"])?>')</script><?
}
else
{
	?><script>change_stock_type('photo')</script><?
}
?>