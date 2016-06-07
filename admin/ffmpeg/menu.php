<?
//Check access
admin_panel_access("settings_ffmpeg");

if(!defined("site_root")){exit();}
?>
<div class="tabbable nav-tabs-custom">
    	<ul class="nav nav-tabs" style="margin:10px 6px 10px 6px">
    		<li <?if(@$section=="ffmpeg"){echo("class='active'");}?>><a href="index.php">FFMPEG <?=word_lang("video")?></a></li>
			<li <?if(@$section=="sox"){echo("class='active'");}?>><a href="sox.php">Sox <?=word_lang("audio")?></a></li>
			<li <?if(@$section=="ffmpeg_cron"){echo("class='active'");}?>><a href="ffmpeg_cron.php">FFMPEG <?=word_lang("Queue")?></a></li>
    	</ul>
    	<div class="box_padding">
