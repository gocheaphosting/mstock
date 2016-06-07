<?
//Check access
admin_panel_access("settings_ffmpeg");

if(!defined("site_root")){exit();}



?>
<form method="post" action="change.php">
<div class="form_field"> 
	<span><b><?=word_lang("enabled")?>:</b></span>
	<input name="ffmpeg" type="checkbox" <?if($global_settings["ffmpeg"]==1){echo("checked");}?>>
</div>


<div class="form_field">
	<span><b><?=word_lang("path")?>:</b></span>
	<input name="fpath" value="<?=$global_settings["ffmpeg_path"]?>" type="text" style="width:300"><br><span class="smalltext">Usually the path is /usr/local/bin/ffmpeg</span>
</div>


<div class="form_field">
	<span><b>Video preview format:</b></span>
	<select name="video_format">
		<option value="mp4" <?if($global_settings["ffmpeg_video_format"]=="mp4"){echo("selected");}?>>MP4 (recommended)</option>
		<option value="flv" <?if($global_settings["ffmpeg_video_format"]=="flv"){echo("selected");}?>>FLV</option>
	</select>
</div>

<div class="form_field">
	<span><b>Preview duration (sec):</b></span>
	<input name="duration" value="<?=$global_settings["ffmpeg_duration"]?>" type="text" style="width:97">
</div>


<div class="form_field">
	<span><b><?=word_lang("preview")?> <?=word_lang("video")?> <?=word_lang("size")?>:</b></span>
	<input name="video_width" value="<?=$global_settings["ffmpeg_video_width"]?>" type="text" style="width:70px;display:inline"> x <input name="video_height" value="<?=$global_settings["ffmpeg_video_height"]?>" type="text" style="width:70px;display:inline">
</div>


<div class="form_field">
	<span><b><?=word_lang("preview")?> <?=word_lang("photo")?> <?=word_lang("size")?>:</b></span>

	<input name="thumb_width" value="<?=$global_settings["ffmpeg_thumb_width"]?>" type="text" style="width:70px;display:inline"> x <input name="thumb_height" value="<?=$global_settings["ffmpeg_thumb_height"]?>" type="text" style="width:70px;display:inline">
</div>

<div class="form_field">
	<span><b>Amount of JPG thumbs:</b></span>
	<input name="frequency" value="<?=$global_settings["ffmpeg_frequency"]?>" type="text" style="width:97px">
</div>

<div class="form_field"> 
	<span><b><?=word_lang("watermark")?>:</b></span>
	<input name="watermark" type="checkbox" value="1" <?if($global_settings["ffmpeg_watermark"]==1){echo("checked");}?>>
</div>




<div class="form_field">
	<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>">
</div>

</form>

