<?
//Check access
admin_panel_access("settings_networks");
?>
<?if(!defined("site_root")){exit();}?>

<p>
1) To enable instagram authorization you should  add <b>a new application</b> here:<br>
<a href="http://instagram.com/developer/#">http://instagram.com/developer/</a>
</p>


<ul>

<li><b>Website:</b> <?=surl?>
</li>


<li><b>OAuth redirect_uri:</b> <?=str_replace("http://","",surl)?>/members/checkinstagram.php
</li>

</ul>

</p>

<p>
2) Fill in the table:
</p>

<form method="post" action="change.php?title=Instagram">
<?
$sql="select activ,consumer_key,consumer_secret from users_qauth where title='Instagram'";
$rs->open($sql);
if(!$rs->eof)
{
?>
<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='instagram_activ'   <?if($rs->row["activ"]==1){echo("checked");}?>>
</div>

<div class='admin_field'>
<span>Application ID:</span>
<input type='text' name='instagram_consumer_key'  style="width:400px" value="<?=$rs->row["consumer_key"]?>">
</div>

<div class='admin_field'>
<span>Application secret:</span>
<input type='text' name='instagram_consumer_secret'  style="width:400px" value="<?=$rs->row["consumer_secret"]?>">
</div>

<div class='admin_field'>
<input type='submit' class="btn btn-primary"  value="<?=word_lang("change")?>">
</div>
<?
}
?>
</form>





