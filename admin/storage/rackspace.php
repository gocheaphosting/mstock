<?

if(!defined("site_root")){exit();}

//Check access
admin_panel_access("settings_storage");

?>

<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">

<p>
<a href="http://www.rackspacecloud.com/2361.html"><b>Rackspace cloud files</b></a> provides unlimited online storage for files and media. You can deliver that content<br> to your users at blazing speeds over Akamai's content delivery network (CDN).
</p>

<p>
All files are stored on the <b>local server first</b> and then they are moved<br> to the clouds hosting <b>ONLY</b> by <a href="index.php?d=5"><b>Cron script</b></a>. The files will not be moved on Rackspace hosting if you don't run the cron script.
</p>

<p>The script creates in your Rackspace account <b>2 containers:</b></p>

<p>
<b>[PREFIX]_files</b> - for the files.<br> 
<b>[PREFIX]_previews</b> - for the previews. 
</p>

<p>From time to time you can change the prefix to organize your file archive better.</p>

<p>
You should check the <a href="../phpini/">php.ini settings</a>:<br>
allow_url_fopen = On<br>
ignore_user_abort = On
</p>

</div>


<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">


<form method="post" action="change_rackspace.php">

<div class='admin_field'>
<span>Rackspace:</span>
<input type='checkbox' name='activ'   <?if($global_settings["rackspace"]==1){echo("checked");}?>>
</div>

<div class='admin_field'>
<span>Container's name prexif:</span>
<input type='text' name='prefix'  style="width:400px" value="<?=$global_settings["rackspace_prefix"]?>">
</div>

<div class='admin_field'>
<span>Username:</span>
<input type='text' name='username'  style="width:400px" value="<?=$global_settings["rackspace_username"]?>">
</div>

<div class='admin_field'>
<span>API Key:</span>
<input type='text' name='api_key'  style="width:400px" value="<?=$global_settings["rackspace_api_key"]?>">
</div>



<div class='admin_field'>
<input type='submit' class="btn btn-primary"  value="<?=word_lang("change")?>">
</div>

</form>


</div>
<div class="subheader">Test</div>
<div class="subheader_text">



<p>The script will upload <a href="test.jpg">the file</a> on Rackspace clouds.</p>


<p>
<a class="btn btn-primary" href="index.php?d=3&action=test"><i class="icon-picture icon-arrow-right icon-white fa fa-upload"></i> Test Rackspace Now</a>
</p>

<?
if(isset($_GET["action"])){


require("../rackspace/cloudfiles.php");


$auth = new CF_Authentication($global_settings["rackspace_username"],$global_settings["rackspace_api_key"]);
$auth->authenticate();
$conn = new CF_Connection($auth);
$images = $conn->create_container($global_settings["rackspace_prefix"]."_test");
$bday = $images->create_object("test.jpg");
$bday->load_from_filename($_SERVER["DOCUMENT_ROOT"].site_root."/admin/storage/test.jpg");
$uri = $images->make_public(3600*24*365*10);


echo("<br><br><b>File URL:</b>".$bday->public_uri()."<br><br>");
echo("<img src='".$bday->public_uri()."'>");

	$container_files = $conn->create_container($global_settings["rackspace_prefix"]."_files");
	$url_files=$container_files->make_public(3600*24);

	$container_previews = $conn->create_container($global_settings["rackspace_prefix"]."_previews");
	$url_previews=$container_previews->make_public(3600*24);

}
?>


</div>

