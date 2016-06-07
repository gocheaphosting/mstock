<?
//Check access
admin_panel_access("settings_ffmpeg");

if(!defined("site_root")){exit();}



?>
<form method="post" action="change_sox.php" Enctype="multipart/form-data">
<div class="form_field"> 
	<span><b><?=word_lang("enabled")?>:</b></span>
	<input name="sox" type="checkbox" value="1" <?if($global_settings["sox"]==1){echo("checked");}?>>
</div>


<div class="form_field">
	<span><b><?=word_lang("path")?>:</b></span>
	<input name="fpath" value="<?=$global_settings["sox_path"]?>" type="text" style="width:300"><br><span class="smalltext">Usually the path is /usr/local/bin/sox</span>
</div>


<div class="form_field">
	<span><b>Library for mp3 preview generation:</b></span>
	<select name="library">
		<option value="sox" <?if($global_settings["sox_library"]=="sox"){echo("selected");}?>>Sox</option>
		<option value="ffmpeg" <?if($global_settings["sox_library"]=="ffmpeg"){echo("selected");}?>>FFMPEG</option>
	</select>
</div>

<div class="form_field">
	<span><b>Preview duration (sec):</b></span>
	<input name="duration" value="<?=$global_settings["sox_duration"]?>" type="text" style="width:97">
</div>



<div class="form_field"> 
	<span><b><?=word_lang("watermark")?> (*.mp3):</b></span>
	<input name="watermark_enable" type="checkbox" value="1" <?if($global_settings["sox_watermark"]==1){echo("checked");}?>>  <span class="smalltext" style="display:inline">Only for Sox. It doesn't work for ffmpeg</span><br>
	<input type="file" name="watermark">
	<?
	if($global_settings["sox_watermark_file"]!="" and file_exists($_SERVER["DOCUMENT_ROOT"].$global_settings["sox_watermark_file"]))
	{
	?>
	<script src="<?=site_root?>/inc/js/mediaelementjs/mediaelement-and-player.min.js"></script>
	<link rel="stylesheet" href="<?=site_root?>/inc/js/mediaelementjs/mediaelementplayer.min.css" />
	<div style="margin-top:5px"><audio id="player2" src="<?=$global_settings["sox_watermark_file"]?>" type="audio/mp3" controls="controls">		
	</audio></div>	

	<script>
		$('audio,video').mediaelementplayer();
	</script>
	
	
		<div style="margin-top:5px"><a href="sox_delete.php" class="btn btn-mini"><?=word_lang("delete")?></a></div>
	<?
	}
	?>
</div>



<div class="form_field">
	<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>">
</div>

</form>

