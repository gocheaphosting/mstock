<?
//Check access
admin_panel_access("settings_networks");
?>
<?if(!defined("site_root")){exit();}?>
<p>
To enable Vkontakte authorization you should do the next:
</p>
<p>
1) Add <b>a new application</b> here:<br>
<a href="http://vk.com/editapp?act=create">http://vk.com/editapp?act=create</a>
</p>




<ul>
<li><b>Type of application:</b> any</li>
<li><b>Title:</b> <?=$global_settings["site_name"]?></li>
<li><b>Description:</b> <?=$global_settings["meta_description"]?></li>
<li><b>Open API - Site URL:</b> <?=surl?></li>



<li><b>Open API - Site Domain:</b> <?=str_replace("http://","",surl)?>
</li>

</ul>


<p>
2) Fill in the table:
</p>

<form method="post" action="change.php?title=Vkontakte">
<?
$sql="select activ,consumer_key,consumer_secret from users_qauth where title='Vkontakte'";
$rs->open($sql);
if(!$rs->eof)
{
?>
<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='vkontakte_activ'   <?if($rs->row["activ"]==1){echo("checked");}?>>
</div>

<div class='admin_field'>
<span>Application ID:</span>
<input type='text' name='vkontakte_consumer_key'  style="width:400px" value="<?=$rs->row["consumer_key"]?>">
</div>

<div class='admin_field'>
<span>Application secret:</span>
<input type='text' name='vkontakte_consumer_secret'  style="width:400px" value="<?=$rs->row["consumer_secret"]?>">
</div>

<div class='admin_field'>
<input type='submit' class="btn btn-primary"  value="<?=word_lang("change")?>">
</div>
<?
}
?>
</form>





