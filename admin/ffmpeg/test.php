<?
//Check access
admin_panel_access("settings_ffmpeg");

if(!defined("site_root")){exit();}
?>
<?
if(!isset($_GET["p"]))
{
?>
	<p>Before using FFMPEG we strongly recommend you to test it.<br><br>
	There is a file <b><?=site_root?>/content/ffmpeg_test.wmv</b> on ftp. The script must create video and jpg previews for the video.</p>
	<input class="btn btn-primary type="button" value="Generate video preview" onClick="location.href='generate.php'">
<?
}
else
{
?>
	<h2>Result:</h2>
	<?
		if($global_settings["ffmpeg_video_format"]=="flv")
		{
			$flv=site_root."/content/thumb.flv";
		}
		else
		{
			$flv=site_root."/content/thumb.mp4";
		}
		if(file_exists($_SERVER["DOCUMENT_ROOT"].$flv))
		{
			?>
			<p>The <b>video preview file</b> has been generated successfully.</p>
			<?
			if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/video_player.tpl"))
			{
				$video_player=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/templates/players/video_player.tpl");
				$video_player=str_replace("{ID}","0",$video_player);
				$video_player=str_replace("{SITE_ROOT}",site_root,$video_player);
				$video_player=str_replace("{VIDEO_WIDTH}",$global_settings["ffmpeg_video_width"],$video_player);
				$video_player=str_replace("{VIDEO_HEIGHT}",$global_settings["ffmpeg_video_height"],$video_player);
				$video_player=str_replace("{PREVIEW_VIDEO}",$flv,$video_player);
				echo($video_player);
			}
			?>
			<br><br>
			<p><b>JPG photo preview:</b></p>
			<table border="0" cellpadding="2" cellspacing="0">
			<tr>
			<?
			$dir = opendir ($DOCUMENT_ROOT.site_upload_directory);
			while ($file = readdir ($dir)) 
			{
				if($file <> "." && $file <> "..")
				{
					if(preg_match("/thumb/i",$file)) 
					{
						if(preg_match("/.jpg$|.jpeg$|.png$|.gif$/i",$file)) 
						{
							?>
							<td><img src="<?=site_root?>/content/<?=$file?>"></td>
							<?
						}
					}
				}
			}
			closedir ($dir);
			?>
			</tr>
			</table>
			<?
		}
		else
		{
			?>
			<div class="alert alert-warning">The error occured during the video preview convertation. Please make sure that FFMPEG installed on your server. Also you can <a href="http://www.cmsaccount.com/contacts/">contact the script developer</a> to resolve the problem.</div>
			<?
		}
	?>
	<br>
	<input class="btn btn-primary" type="button" value="Test once more" onClick="location.href='generate.php'">
<?
}
?>