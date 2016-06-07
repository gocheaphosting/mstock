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
		
		<?if($global_settings["bigstockphoto_contributor"]==""){?>
		<div class="search_title2"><b><?=word_lang("Contributor")?>:</b></div>
		<div class="search_text2">
			<input type='text' name='author' style="width:175px" class='ibox form-control' value='<?if(isset($_REQUEST["author"])){echo(result($_REQUEST["author"]));}?>'>
		</div>
		<?}?>
	
		<div class="search_title2"><b><?=word_lang("type")?>:</b></div>
		<div class="search_text2">
			<select name="stock_type" style="width:175px" class='ibox form-control'>
			<?
			$list_stocktypes=array("","photo","illustration","vector");
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
				$sql="select id,title from category_stock where stock='bigstockphoto' order by title";
				$rs->open($sql);
				while(!$rs->eof)
				{
					$sel="";
					if((isset($_REQUEST["category"]) and $_REQUEST["category"] == $rs->row["title"]) or (!isset($_REQUEST["category"]) and $rs->row["title"] == $global_settings["bigstockphoto_category"]))
					{
						$sel="selected";
					}
					?>
					<option value="<?=$rs->row["title"]?>" <?=$sel?>><?=$rs->row["title"]?></option>
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
			$list_language["es"] ="Spanish";
			$list_language["de"] ="German";
			$list_language["pt"] ="Portuguese";
			$list_language["it"] ="Italian";
			$list_language["nl"] ="Dutch";
			$list_language["fr"] ="French";
			$list_language["ja"] ="Japanese";
			$list_language["zh"] ="Chinese";
			$list_language["ru"] ="Russian";
			$list_language["cs"] ="Czech";
			$list_language["da"] ="Danish";
			$list_language["fi"] ="Finnish";
			$list_language["hu"] ="Hungarian";
			$list_language["ko"] ="Korean";
			$list_language["nb"] ="Norwegian";
			$list_language["pl"] ="Polish";
			$list_language["sv"] ="Swedish";
			$list_language["th"] ="Thai";
			$list_language["tr"] ="Turkish";

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
			$list_orientation[""]="";
			$list_orientation["h"]="horizontal";
			$list_orientation["v"]="vertical";
			foreach ($list_orientation as $key => $value) 
			{
				$sel="";
				if($key==@$_REQUEST["orientation"])
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

