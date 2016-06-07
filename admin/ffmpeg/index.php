<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_ffmpeg");
$section="ffmpeg";
?>
<? include("../inc/begin.php");?>





<? include("menu.php");?>



<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">
	<p><a href="https://ffmpeg.org/"><b>FFMPEG</b></a> is a library which allows to generate <b> *.mp4, *.flv and *.jpg previews</b> for a video file. You should ask your hosting support if your server supports the software. Otherwise you have to upload the video's previews separately.</p>
	
	<p>FFMPEG is able to add a <b>watermark</b> for a video file too.</p>
	
	<p>Also you can use FFMPEG for the <a href="sox.php"><b>*.mp3 audio preview</b> </a> creation.</p>
	
	<p>FFMPEG must be installed with <b>libx264 codec</b>. The php function <b>exec()</b> must be enabled on the server.</p>
	
	<p>You can modify the ffmpeg command in the file: <b>/admin/function/functions.php</b>, function: <b>generate_flv()</b>.</p>
	
	<p>If something doesn't work you have to <a href="http://www.cmsaccount.com/contacts/"><b>contact the script developer</b></a> to set, test, change the exec command. It is an external software. Its functionality strongly depends on the server's settings. Sometimes it requires small adjustments.</p>
</div>

<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">
<?include("settings.php");?>
</div>

<div class="subheader"><?=word_lang("test")?></div>
<div class="subheader_text">
<?include("test.php");?>
</div>






</div>








<? include("../inc/end.php");?>