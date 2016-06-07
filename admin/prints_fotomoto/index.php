<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_fotomoto");
?>
<? include("../inc/begin.php");?>






<h1><?=word_lang("Fotomoto prints service")?></h1>

<div class="box box_padding">

<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">

<p><a href="http://www.fotomoto.com/">Fotomoto</a> is a print-on-demand e-commerce widget that integrates seamlessly into your existing website. Just add our code to your site, sit back, and start making money.</p>

<form method="post" action="change.php">

	<div class='admin_field'>
	<span>Fotomoto Store ID:</span>
	<input type="text" name="fotomoto_id" value="<?=$global_settings["fotomoto_id"]?>" style="width:350px"><br>
	</div>
	
	<div class='admin_field'>
	<input  class="btn btn-primary" type="submit" value="<?=word_lang("save")?>">
	</div>
</form>

</div>

<div class="subheader">How to integrate it</div>
<div class="subheader_text">

	<p>It is very simple. You should register your store at <a href="http://www.fotomoto.com/">fotomoto.com</a>, set the store ID and add <b>{FOTOMOTO}</b> code somewhere in the template on ftp:<br>
	
	<b><?=site_root?>/<?=$site_template_url?>item_photo.tpl</b></p>

</div>

</div>

<? include("../inc/end.php");?>