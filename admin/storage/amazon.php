<?
//Check access
admin_panel_access("settings_storage");


if(!defined("site_root")){exit();}
?>

<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">

<p>
<a href="http://aws.amazon.com/s3/"><b>Amazon Simple Storage Service</b></a> provides a simple web services interface that can be used to store and retrieve any amount of data, at any time, from anywhere on the web.
</p>

<p>
All files are stored on the <b>local server first</b> and then they are moved<br> to the Amazon S3 <b>ONLY</b> by <a href="index.php?d=5"><b>Cron script</b></a>. The files will not be moved on Amazon S3 if you don't run the cron script.
</p>

<p>The script creates in your Amazon S3 account <b>2 buckets:</b></p>

<p>
<b>[PREFIX]-files</b> - for the files.<br> 
<b>[PREFIX]-previews</b> - for the previews. 
</p>

<p>From time to time you can change the prefix to organize your file archive better. We recomment you to <b>test the process every time</b> when you change prefix name.</p>

<p>
You should check the <a href="../phpini/">php.ini settings</a>:<br>
allow_url_fopen = On<br>
ignore_user_abort = On
</p>

</div>


<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">

<form method="post" action="change_amazon.php">

<div class='admin_field'>
<span>Amazon S3:</span>
<input type='checkbox' name='activ'   <?if($global_settings["amazon"]==1){echo("checked");}?>>
</div>

<div class='admin_field'>
<span>Bucket's name prexif:</span>
<input type='text' name='prefix'  style="width:400px" value="<?=$global_settings["amazon_prefix"]?>">
</div>

<div class='admin_field'>
<span>Access Key ID:</span>
<input type='text' name='username'  style="width:400px" value="<?=$global_settings["amazon_username"]?>">
</div>

<div class='admin_field'>
<span>Secret Access Key:</span>
<input type='text' name='api_key'  style="width:400px" value="<?=$global_settings["amazon_api_key"]?>">
</div>

<div class='admin_field'>
<span>Region:</span>
<select name='region'  style="width:200px">
<?
foreach ($amazon_region as $key => $value) 
{
$sel="";
if($key==$global_settings["amazon_region"]){$sel="selected";}
?>
<option value="<?=$key?>" <?=$sel?>><?=$value?></option>
<?
}
?>
</select>
</div>



<div class='admin_field'>
<input type='submit' class="btn btn-primary"  value="<?=word_lang("change")?>">
</div>

</form>

</div>
<div class="subheader">Test</div>
<div class="subheader_text">

<p>The script will upload <a href="test.jpg">the file</a> on Amazon S3.</p>


<p>
<a class="btn btn-primary" href="amazon_test.php"><i class="icon-picture icon-arrow-right icon-white fa fa-upload"></i> Test Amazon S3 Now"</a>
</p>


</div>

