<?
//Check access
admin_panel_access("settings_networks");
?>
<?if(!defined("site_root")){exit();}?>

<p>
To enable Facebook authorization you should do the next:
</p>
<p>
1) Add <b>a new application</b> here:<br>
<a href="https://www.facebook.com/developers/apps.php">https://www.facebook.com/developers/apps.php</a>
</p>


<p>
2) Click <b>Add application</b> and edit the settings <b>Web site</b>:

<ul>

<li><b>Site URL:</b> <?=surl?>
</li>


<li><b>Site Domain:</b> <?=str_replace("http://","",surl)?>
</li>

</ul>

</p>

<p>
3) Fill in the table:
</p>

<form method="post" action="change.php?title=Facebook">
<?
$sql="select activ,consumer_key,consumer_secret from users_qauth where title='Facebook'";
$rs->open($sql);
if(!$rs->eof)
{
?>
<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='facebook_activ'   <?if($rs->row["activ"]==1){echo("checked");}?>>
</div>

<div class='admin_field'>
<span>Application ID:</span>
<input type='text' name='facebook_consumer_key'  style="width:400px" value="<?=$rs->row["consumer_key"]?>">
</div>

<div class='admin_field'>
<span>Application secret:</span>
<input type='text' name='facebook_consumer_secret'  style="width:400px" value="<?=$rs->row["consumer_secret"]?>">
</div>

<div class='admin_field'>
<input type='submit' class="btn btn-primary"  value="<?=word_lang("change")?>">
</div>
<?
}
?>
</form>





