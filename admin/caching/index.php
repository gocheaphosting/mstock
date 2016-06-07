<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("templates_caching");
?>
<? include("../inc/begin.php");?>


<h1><?=word_lang("caching")?>:</h1>

<div class="box box_padding">

<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">

<p>
The script uses Smarty caching system which allows to decrease server's loading. The templates are generated one time, saved and later used without the repeat requests to the database and server.</p>
<p>
You can set time of automatic cache file's refresh or prohibit caching at all (it is useful if you work on the templates and don't want to click "Refresh" every time after you change a template's file).</p>



<form method="post" action="enable.php">
<div class="form_field">
<input type="checkbox" value="1" name="caching" <?if($global_settings["caching"]){echo("checked");}?>> <b>Enable</b> Smarty caching.
</div>

<div class="form_field">
<input type="submit" value="<?=word_lang("save")?>" class="btn btn-primary">
</div>
</form>

</div>

<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">

<p>
<a class="btn btn-danger" href="refresh.php?id=0"><i class="icon-refresh icon-white"></i> <?=word_lang("refresh all files")?></a>
</p>

<?
$sql="select * from caching";
$rs->open($sql);
if(!$rs->eof)
{
?>
	<form method="post" action="change.php">
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("enabled")?></b></th>
	<th><b><?=word_lang("title")?></b></th>
	<th><b><?=word_lang("refresh every X hours")?></b></th>
	<th><b><?=word_lang("refresh")?></b></th>
	</tr>
	<?
	$tr=1;
	while(!$rs->eof)
	{
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?>>
		<td><input name="ch<?=$rs->row["id"]?>" type="checkbox" <?if($rs->row["time_refresh"]>=0){echo("checked");}?>></td>
		<td><?=$rs->row["title"]?></td>
		<td><input name="time_refresh<?=$rs->row["id"]?>" type="text" value="<?if($rs->row["time_refresh"]>=0){echo($rs->row["time_refresh"]);}else{echo(0);}?>" style="width:80px"></td>
		<td><div class="link_delete"><a href="refresh.php?id=<?=$rs->row["id"]?>"><?=word_lang("refresh now")?></a></div></td>
		</tr>
		<?
		$tr++;
		$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
	<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin:10px 0px 0px 6px">
	</form><br>
<?
}
?>

<p>You should set <b>0</b> if you want to disable an automatic refresh.</p>

</div>


<div class="subheader">Cron</div>
<div class="subheader_text">

All cached files are stored into the /cache/ directory. Sometimes (especially when you have many publications on the site) the quantity of the files grows rapidly. We advice you to clear the cached files from time to time. You can do that automatically by the cron script. If you open the URL <b><?=surl?><?=site_root?>/members/cron_delete_cache.php</b> in your browswer all cache will be removed.<br><br>

<b>Examples of the cron commands:</b>

<ul>
<li>/usr/bin/lynx -source <?=surl?><?=site_root?>/members/cron_delete_cache.php</li>
<li>GET <?=surl?><?=site_root?>/members/cron_delete_cache.php > /dev/null</li>
</ul>

You should run the cron script once per day for example.

</div>

</div>

<? include("../inc/end.php");?>