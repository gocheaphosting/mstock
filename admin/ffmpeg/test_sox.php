<?
//Check access
admin_panel_access("settings_ffmpeg");

if(!defined("site_root")){exit();}
?>
<?
if(!isset($_GET["p"]))
{
?>
	<p>Before using Sox we strongly recommend you to test it.<br><br>
	There is a file <b><?=site_root?>/content/sox_test.mp3</b> on ftp. The script must create *.mp3 preview for the audio.</p>
	<input class="btn btn-primary type="button" value="Generate mp3 preview" onClick="location.href='generate_sox.php'">
<?
}
else
{
?>
	<h2>Result:</h2>
	<?
		$flv=site_root."/content/sox_preview.mp3";
		
		if(file_exists($_SERVER["DOCUMENT_ROOT"].$flv))
		{
			?>
			<p>The <b>audio preview file</b> has been generated successfully.</p>
			<script src="<?=site_root?>/inc/js/mediaelementjs/mediaelement-and-player.min.js"></script>
			<link rel="stylesheet" href="<?=site_root?>/inc/js/mediaelementjs/mediaelementplayer.min.css" />
			<div style="margin-top:5px"><audio id="player2" src="<?=$flv?>" type="audio/mp3" controls="controls">		
			</audio></div>	

			<script>
				$('audio,video').mediaelementplayer();
			</script>
			<?
		}
		else
		{
			?>
			<div class="alert">The error occured during the audio preview convertation. Please make sure that Sox or FFMEPG installed on your server. Also you can <a href="http://www.cmsaccount.com/contacts/">contact the script developer</a> to resolve the problem.</div>
			<?
		}
	?>
	<br>
	<input class="btn btn-primary" type="button" value="Test once more" onClick="location.href='generate_sox.php'">
<?
}
?>