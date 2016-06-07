<?
if(!defined("site_root")){exit();}
?>

<script>
	fields_photo = new Array("license","languages","orientation","age","model","gender","ethnicity","people_number","language","color");
	fields_video = new Array("license","aspect_ratio","duration_video","language","resolution","age","model","gender","ethnicity","people_number");
	fields_audio = new Array("album_title","artists","bmp","duration_audio","genre","instrumental","instruments","lyrics","moods","vocal_description");

	function change_stock_type(value)
	{
		if(value == '' || value == 'photo' || value == 'illustration')
		{		
			deactivate_fields('video');
			deactivate_fields('audio');
			activate_fields('photo');
		}
		
		if(value == 'videos')
		{
			deactivate_fields('photo');
			deactivate_fields('audio');	
			activate_fields('video');
		}
		
		if(value == 'music')
		{
			deactivate_fields('photo');
			deactivate_fields('video');
			activate_fields('audio');		
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
		if(value == 'audio')
		{
			fields_stock = fields_audio;
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
		if(value == 'audio')
		{
			fields_stock = fields_audio;
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
		
		<?if($global_settings["shutterstock_contributor"]==""){?>
		<div class="search_title2"><b><?=word_lang("Contributor")?>:</b></div>
		<div class="search_text2">
			<input type='text' name='author' style="width:175px" class='ibox form-control' value='<?if(isset($_REQUEST["author"])){echo(result($_REQUEST["author"]));}?>'>
		</div>
		<?}?>
	
		<div class="search_title2"><b><?=word_lang("type")?>:</b></div>
		<div class="search_text2">
			<select name="stock_type" style="width:175px" class='ibox form-control' onChange='change_stock_type(this.value)'>
			<?
			$list_stocktypes=array("","photo","illustration","vector","videos","music");
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
				$sql="select id,title from category_stock where stock='shutterstock' order by title";
				$rs->open($sql);
				while(!$rs->eof)
				{
					$sel="";
					if((isset($_REQUEST["category"]) and $_REQUEST["category"] == $rs->row["id"]) or (!isset($_REQUEST["category"]) and $rs->row["id"] == $global_settings["shutterstock_category"]))
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
			$list_license=array("commercial", "editorial", "enhanced");
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
			$list_language=array("cs", "da", "de", "en", "es", "fi", "fr", "hu", "it", "ja", "ko", "nb", "nl", "pl", "pt", "ru", "sv", "th", "tr", "zh");
			$lang_others["ko"] = "Korean";
			$lang_others["nb"] = "NB";
			$lang_others["zh"] = "Chinese";
			foreach ($list_language as $key => $value) 
			{
				$sel="";
				if($value=="en" and !isset($_REQUEST["language"]))
				{
					$sel="selected";
				}
				
				if($value==@$_REQUEST["language"])
				{
					$sel="selected";
				}
				?>
				<option value='<?=$value?>' <?=$sel?>><?if(isset($lang_symbol_inv[$value])){echo($lang_symbol_inv[$value]);}else{echo(@$lang_others[$value]);}?></option>
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
		
		
		<div class="search_title2 field_aspect_ratio"><b><?=word_lang("aspect ratio")?>:</b></div>
		<div class="search_text2 field_aspect_ratio">
			<select name="aspect_ratio" style="width:175px" class='ibox form-control'>
			<?
			$list_aspect_ratio[""]="";
			$list_aspect_ratio["4_3"]="4:3";
			$list_aspect_ratio["16_9"]="16:9";
			$list_aspect_ratio["nonstandard"]=word_lang("other");

			foreach ($list_aspect_ratio as $key => $value) 
			{
				$sel="";
				if($key==@$_REQUEST["aspect_ratio"])
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
		
		<div class="search_title2 field_resolution"><b><?=word_lang("resolution")?>:</b></div>
		<div class="search_text2 field_resolution">
			<select name="resolution" style="width:175px" class='ibox form-control'>
			<?
			$list_resolution[""]="";
			$list_resolution["4k"]="4k";
			$list_resolution["standard_definition"]="SD";
			$list_resolution["high_definition"]="HD";

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
		
		
		<div class="search_title2 field_duration_video"><b><?=word_lang("duration")?>:</b></div>
		<div class="search_text2 field_duration_video">
			<script>
				$(function() {
				$( "#slider-range" ).slider({
				range: true,
				min: 0,
				max: 7200,
				values: [<?=$duration_video1?>,<?=$duration_video2?>],
				slide: function( event, ui ) {
				$( "#duration_video" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
				}
				});
				$( "#duration_video" ).val($( "#slider-range" ).slider( "values", 0 ) +
				" - " + $( "#slider-range" ).slider( "values", 1 ) );

				});
			</script>
			<div class="box_slider">
				<input type="hidden" id="duration_video" name="duration_video" value="<?=$duration_video1?> - <?=$duration_video2?>">
				<div id="slider-range"></div>
				<div class="box_slider2">0m</div>
				<div class="box_slider3">120m</div>
			</div>
		</div>
			
			
			
		<div class="search_title2 field_album_title"><b><?=word_lang("Album title")?>:</b></div>
		<div class="search_text2 field_album_title">
			<input type='text' name='album_title' style="width:175px" class='ibox form-control' value='<?=result(@$_REQUEST["album_title"])?>'>
		</div>
		
		<div class="search_title2 field_artists"><b><?=word_lang("Artists")?>:</b></div>
		<div class="search_text2 field_artists">
			<input type='text' name='artists' style="width:175px" class='ibox form-control' value='<?=result(@$_REQUEST["artists"])?>'>
		</div>
		
		<div class="search_title2 field_genre"><b><?=word_lang("Genre")?>:</b></div>
		<div class="search_text2 field_genre">
			<select name="genre" style="width:175px" class='ibox form-control'>
			<?
			$list_genre=array("", "Blues", "Children", "Classical", "Country", "Dance/Electronic", "Hip-Hop/Rap", "Holiday", "Jazz", "New Age", "Pop/Rock", "R&B/Soul", "Reggae/Ska", "Spiritual", "World/International");
			foreach ($list_genre as $key => $value) 
			{
				$sel="";
				if($value==@$_REQUEST["genre"])
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
		
		<div class="search_title2 field_duration_audio"><b><?=word_lang("duration")?>:</b></div>
		<div class="search_text2 field_duration_audio">
			<script>
			$(function() {
			$( "#slider-range2" ).slider({
			range: true,
			min: 0,
			max: 7200,
			values: [<?=$duration_audio1?>,<?=$duration_audio2?>],
			slide: function( event, ui ) {
			$( "#duration_audio" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
			}
			});
			$( "#duration_audio" ).val($( "#slider-range2" ).slider( "values", 0 ) +
			" - " + $( "#slider-range2" ).slider( "values", 1 ) );
			});
			</script>
			<div class="box_slider">
				<input type="hidden" id="duration_audio" name="duration_audio" value="<?=$duration_audio1?> - <?=$duration_audio2?>">
				<div id="slider-range2"></div>
				<div class="box_slider2">0m</div>
				<div class="box_slider3">120m</div>
			</div>
		</div>
		
		
		<div class="search_title2 field_bmp"><b><?=word_lang("Beats per minute")?>:</b></div>
		<div class="search_text2 field_bmp">
			<?
			$bmp1=0;
			$bmp2=120;
			if(isset($_REQUEST["bmp"]))
			{
				$bmp_mass=explode(" - ",result($_REQUEST["bmp"]));
				if(isset($bmp_mass[0]) and isset($bmp_mass[1]))
				{
					$bmp1=(int)$bmp_mass[0];
					$bmp2=(int)$bmp_mass[1];
				}
			}
			?>
			<script>
			$(function() {
			$( "#slider-range3" ).slider({
			range: true,
			min: 0,
			max: 240,
			values: [<?=$bmp1?>,<?=$bmp2?>],
			slide: function( event, ui ) {
			$( "#bmp" ).val(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
			}
			});
			$( "#bmp" ).val($( "#slider-range2" ).slider( "values", 0 ) +
			" - " + $( "#slider-range2" ).slider( "values", 1 ) );
			});
			</script>
			<div class="box_slider">
				<input type="hidden" id="bmp" name="bmp" value="<?=$bmp1?> - <?=$bmp2?>">
				<div id="slider-range3"></div>
				<div class="box_slider2">0 BMP</div>
				<div class="box_slider3">240 BMP</div>
			</div>
		</div>
		
		<div class="search_title2 field_instrumental"><b><?=word_lang("Instrumental")?>:</b></div>
			
		<div class="search_text2 field_instrumental">
			<input type="checkbox" name="instrumental" <?if(@$_REQUEST["instrumental"]==1){echo("checked");}?> value="1">
		</div>	
		
		<div class="search_title2 field_instruments"><b><?=word_lang("Instruments")?>:</b></div>
		<div class="search_text2 field_instruments">
			<input type='text' name='instruments' style="width:175px" class='ibox form-control' value='<?=result(@$_REQUEST["instruments"])?>'>
		</div>
		
		<div class="search_title2 field_lyrics"><b><?=word_lang("Lyrics")?>:</b></div>
		<div class="search_text2 field_lyrics">
			<input type='text' name='lyrics' style="width:175px" class='ibox form-control' value='<?=result(@$_REQUEST["lyrics"])?>'>
		</div>
		
		<div class="search_title2 field_moods"><b><?=word_lang("Moods")?>:</b></div>
		<div class="search_text2 field_moods">
			<input type='text' name='moods' style="width:175px" class='ibox form-control' value='<?=result(@$_REQUEST["moods"])?>'>
		</div>
		
		<div class="search_title2 field_vocal_description"><b><?=word_lang("Vocal description")?>:</b></div>
		<div class="search_text2 field_vocal_description">
			<input type='text' name='vocal_description' style="width:175px" class='ibox form-control' value='<?=result(@$_REQUEST["vocal_description"])?>'>
		</div>
		
		
		<div class="search_title2 field_age"><b><?=word_lang("Age")?>:</b></div>
		<div class="search_text2 field_age">
			<select name="age" style="width:175px" class='ibox form-control'>
			<?
			$list_age=array("","infants", "children", "teenagers", "20s", "30s", "40s", "50s", "60s", "older");
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
			$list_ethnicity=array("","african", "african_american", "black", "brazilian", "chinese", "caucasian", "east_asian", "hispanic", "japanese", "middle_eastern", "native_american", "pacific_islander", "south_asian", "southeast_asian", "other");
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
			$list_peoplenumber=array("","0","1","2","3","4");
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