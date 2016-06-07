<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_site");
?>
<? include("../inc/begin.php");?>



<h1><?=word_lang("settings")?>:</h1>





<form method="post" action="settings_change.php">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover">
	<?
	$sql="select * from settings where stype='site' and priority>0 group by setting_key  order by priority,name ";
	$rs->open($sql);
	$tr=1;
	while(!$rs->eof)
	{
		?>
		<tr valign="top" <?if($tr%2==0){echo("class='snd'");}?>>
		<td class="big"><?=word_lang($rs->row["name"])?>:</td>
		<td class="gray" style="width:70%">
		<?
		if($rs->row["setting_key"]=='lightbox_photo' or $rs->row["setting_key"]=='lightbox_video' or $rs->row["setting_key"]=='userupload' or $rs->row["setting_key"]=='usa_2257' or $rs->row["setting_key"]=='allow_photo' or $rs->row["setting_key"]=='allow_video' or $rs->row["setting_key"]=='allow_audio' or $rs->row["setting_key"]=='blog' or $rs->row["setting_key"]=='messages' or $rs->row["setting_key"]=='testimonials' or $rs->row["setting_key"]=='reviews' or $rs->row["setting_key"]=='friends' or $rs->row["setting_key"]=='prints' or $rs->row["setting_key"]=='photo_remote' or $rs->row["setting_key"]=='video_remote' or $rs->row["setting_key"]=='audio_remote' or $rs->row["setting_key"]=='printsonly' or $rs->row["setting_key"]=='watermarkinfo' or $rs->row["setting_key"]=='allow_vector' or $rs->row["setting_key"]=='vector_remote' or $rs->row["setting_key"]=='credits' or $rs->row["setting_key"]=='download_sample' or $rs->row["setting_key"]=='subscription' or $rs->row["setting_key"]=='subscription_only' or $rs->row["setting_key"]=='common_account' or $rs->row["setting_key"]=='related_items' or $rs->row["setting_key"]=='zoomer' or $rs->row["setting_key"]=='moderation' or $rs->row["setting_key"]=='prints_users' or $rs->row["setting_key"]=='model' or $rs->row["setting_key"]=='show_model' or $rs->row["setting_key"]=='flash' or $rs->row["setting_key"]=='examination' or $rs->row["setting_key"]=='google_coordinates' or $rs->row["setting_key"]=='exif' or $rs->row["setting_key"]=='affiliates' or $rs->row["setting_key"]=='google_captcha' or $rs->row["setting_key"]=='site_guest' or $rs->row["setting_key"]=='java_uploader' or $rs->row["setting_key"]=='flash_uploader' or $rs->row["setting_key"]=='jquery_uploader' or 
		 $rs->row["setting_key"]=='plupload_uploader' or $rs->row["setting_key"]=='seller_prices' or $rs->row["setting_key"]=='language_detection' or $rs->row["setting_key"]=='adult_content' or $rs->row["setting_key"]=='prints_photos' or $rs->row["setting_key"]=='prints_vectors' or $rs->row["setting_key"]=='auto_paging' or $rs->row["setting_key"]=='auto_paging_default' or $rs->row["setting_key"]=='auth_freedownload' or $rs->row["setting_key"]=='auth_rating' or $rs->row["setting_key"]=='multilingual_categories' or 
		 $rs->row["setting_key"]=='multilingual_publications' or $rs->row["setting_key"]=='rights_managed' or $rs->row["setting_key"]=='royalty_free' or $rs->row["setting_key"]=='rights_managed_sellers' or $rs->row["setting_key"]=='show_content_type' or $rs->row["setting_key"]=='support' or $rs->row["setting_key"]=='search_history' or $rs->row["setting_key"]=='prints_lab' or $rs->row["setting_key"]=='grid' or $rs->row["setting_key"]=='fixed_width' or $rs->row["setting_key"]=='fixed_height' or $rs->row["setting_key"]=='contacts_price' or $rs->row["setting_key"]=='exclusive_price' or $rs->row["setting_key"]=='left_search' or $rs->row["setting_key"]=='left_search_default' or $rs->row["setting_key"]=='credits_currency' or $rs->row["setting_key"]=='taxes_cart' or $rs->row["setting_key"]=='no_calculation' or $rs->row["setting_key"]=='users_rating' or $rs->row["setting_key"]=='users_rating_limited')
		{
			?>
			<input type="hidden" name="p_<?=$rs->row["setting_key"]?>" value=""><input type="checkbox" name="a_<?=$rs->row["setting_key"]?>" value="1" <?if($rs->row["activ"]==1){echo("checked");}?>>&nbsp;&nbsp;&nbsp;<?=word_lang("enable")?>
			<?
		}
		elseif($rs->row["setting_key"]=='userstatus')
		{
			?>
			<select  name="p_<?=$rs->row["setting_key"]?>" style="width:150px" class="ibox form-control">
			<?
			$sql="select * from user_category order by priority";
			$dr->open($sql);
			while(!$dr->eof)
			{
				$sel="";
				if($rs->row["svalue"]==$dr->row["name"]){$sel="selected";}
				?>
				<option value="<?=$dr->row["name"]?>" <?=$sel?>><?=$dr->row["name"]?></option>
				<?
			$dr->movenext();
			}
			?>
			</select>
			<?
		}
		elseif($rs->row["setting_key"]=='catalog_view')
		{
			?>
			<select  name="p_<?=$rs->row["setting_key"]?>" style="width:350px" class="ibox form-control">
				<option value="grid" <?if($rs->row["svalue"]=="grid"){echo("selected");}?>><?=word_lang("grid")?></option>
				
				<option value="fixed_width" <?if($rs->row["svalue"]=="fixed_width"){echo("selected");}?>><?=word_lang("fixed width")?></option>
				
				<option value="fixed_height" <?if($rs->row["svalue"]=="fixed_height"){echo("selected");}?>><?=word_lang("fixed height")?></option>
			</select>
			<?
		}
		elseif($rs->row["setting_key"]=='show_users_type')
		{
			?>
			<select  name="p_<?=$rs->row["setting_key"]?>" style="width:150px" class="ibox form-control">
				<option value="login" <?if($rs->row["svalue"]=="login"){echo("selected");}?>><?=word_lang("login")?></option>
				<option value="name" <?if($rs->row["svalue"]=="name"){echo("selected");}?>><?=word_lang("name")?></option>
			</select>
			<?
		}
		elseif($rs->row["setting_key"]=='image_resize')
		{
			?>
			<select  name="p_<?=$rs->row["setting_key"]?>" style="width:150px" class="ibox form-control">
				<option value="GD" <?if($rs->row["svalue"]=="GD"){echo("selected");}?>>GD</option>
				<option value="ImageMagick" <?if($rs->row["svalue"]=="ImageMagick"){echo("selected");}?>>ImageMagick</option>
			</select>
			<?
		}
		elseif($rs->row["setting_key"]=='sorting_catalog')
		{
			?>
			<select  name="p_<?=$rs->row["setting_key"]?>" style="width:200px" class="ibox form-control">
				<option value="downloaded" <?if($rs->row["svalue"]=="downloaded"){echo("selected");}?>><?=word_lang("most downloaded")?></option>
				<option value="popular" <?if($rs->row["svalue"]=="popular"){echo("selected");}?>><?=word_lang("most popular")?></option>
				<option value="date" <?if($rs->row["svalue"]=="date"){echo("selected");}?>><?=word_lang("date")?></option>
				<!--<option value="title" <?if($rs->row["svalue"]=="title"){echo("selected");}?>><?=word_lang("title")?></option>-->
				<option value="rated" <?if($rs->row["svalue"]=="rated"){echo("selected");}?>><?=word_lang("top rated")?></option>
				<!--<option value="random" <?if($rs->row["svalue"]=="random"){echo("selected");}?>><?=word_lang("random")?></option>-->
			</select>
			<?
		}
		elseif($rs->row["setting_key"]=='weight')
		{
			?>
			<select  name="p_<?=$rs->row["setting_key"]?>" style="width:200px" class="ibox form-control">
				<option value="kg" <?if($rs->row["svalue"]=="kg"){echo("selected");}?>><?=word_lang("kg")?></option>
				<option value="lbs" <?if($rs->row["svalue"]=="lbs"){echo("selected");}?>><?=word_lang("lbs")?></option>
			</select>
			<?
		}
		elseif($rs->row["setting_key"]=='photo_uploader' or $rs->row["setting_key"]=='video_uploader' or $rs->row["setting_key"]=='audio_uploader' or $rs->row["setting_key"]=='vector_uploader')
		{
			?>
			<select  name="p_<?=$rs->row["setting_key"]?>" style="width:200px" class="ibox form-control">
				<option value="usual uploader" <?if($rs->row["svalue"]=="usual uploader"){echo("selected");}?>><?=word_lang("usual uploader")?></option>
				<option value="jquery uploader" <?if($rs->row["svalue"]=="jquery uploader"){echo("selected");}?>><?=word_lang("jquery uploader")?></option>
			</select>
			<?
		}
		elseif($rs->row["setting_key"]=='meta_keywords' or $rs->row["setting_key"]=='meta_description' or $rs->row["setting_key"]=='company_address')
		{
			?>
			<textarea   name="p_<?=$rs->row["setting_key"]?>" style="width:400px;height:90px" class="ibox form-control"><?=$rs->row["svalue"]?></textarea>
			<?
		}
		elseif($rs->row["setting_key"]=='content_type')
		{
			?>
			<select  name="p_<?=$rs->row["setting_key"]?>" style="width:150px" class="ibox form-control">
			<?
			$sql="select * from content_type order by priority";
			$dr->open($sql);
			while(!$dr->eof)
			{
				$sel="";
				if($rs->row["svalue"]==$dr->row["name"]){$sel="selected";}
				?>
				<option value="<?=$dr->row["name"]?>" <?=$sel?>><?=$dr->row["name"]?></option>
				<?
				$dr->movenext();
			}
			?>
			</select>
			<?
		}
		else
		{
			?><input type="text" name="p_<?=$rs->row["setting_key"]?>" class="ibox form-control" style="width:400px" value="<?=$rs->row["svalue"]?>"><input type="hidden" name="a_<?=$rs->row["setting_key"]?>" value="0"><?
		}
		?>
		</td>
		</tr>
		<?
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
	
	
	<div id="button_bottom_static">
		<div id="button_bottom_layout"></div>
		<div id="button_bottom"><input type="submit" value="<?=word_lang("save")?>" class="btn btn-primary"></div></div>
</form>













<? include("../inc/end.php");?>