<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_phpini");
?>
<? include("../inc/begin.php");?>






<h1>PHP.ini configuration:</h1>

<p>
The script requires several php.ini modifications for the stable work. You should ask your hosting support how you can edit php.ini file. There can be several methods: global php.ini modification, creation of a local php.ini, .htaccess instructions or php code. It depends on the server's settings.
</p>

<p>You shoud pay attention <b>the next php.ini settings:</b></p>

<ul>
<li><b>upload_max_filesize</b> - It is max filesize which you may upload on the server.</li>

<li><b>post_max_filesize</b> - It is max filesize which you may upload on the server by java uploader.</li>

<li><b>memory_limit</b> - It is max RAM memory of the server which the script may use. It is critical settings for the photo stock sites. When you upload a photo the script generates/resizes 2 previews. It requires many RAM memory especially for the high-resolution photos. It must be minimum 128M for 2000-3000px images.</li>

<li><b>max_execution_time</b> - The script may work only for the time and then the server terminates it. If you work with the large files you should increase the setting.</li>

</ul>

<p><b>Other important php.ini settings:</b></p>

<ul>
<li><b>ignore_user_abort = On</b> - If the buyers download large files then the setting must be enabled to prevent a disconnection.</li>
<li><b>allow_url_fopen = On</b> - If you use Facebook/Twitter authorization or Rackspace/Amazom S3 file storage then the setting must be enabled.</li>
<li><b>FFMPEG</b> - If you use ffmpeg for the video convertation then the php.ini file must contain FFMPEG section.</li>

<li><b>Safe mode</b> - If you use Plesk hosting cpanel and have problems with the file's upload then you should try to disable Safe mode. On hosting CPanel the script works perfect with enabled Safe mode.</li>
</ul>



<p>Below <b>your php.ini file</b>. if you don't see anything then it is prohibited by the security settings.</p>
<br>
<p><iframe src="phpinfo.php" style="width:100%;height:500px"></iframe></p>




<? include("../inc/end.php");?>