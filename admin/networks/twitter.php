<?
//Check access
admin_panel_access("settings_networks");
?>
<?if(!defined("site_root")){exit();}?>





<p>
To enable Twitter authorization you should do the next:
</p>

<p>
1) Add <b>a new application</b> here:<br>
<a href="http://dev.twitter.com/apps/new">http://dev.twitter.com/apps/new</a>
</p>

<div class="list_steps">
<ul>

<li><b>Application Name:</b> Your site name
</li>


<li><b>Description:</b> Your site description
</li>

<li><b>Application Website:</b> <?=surl?>
</li>


<li><b>Organization:</b> Your company name
</li>

<li><b>Application Type:</b> Browser
</li>

<li><b>Callback URL:</b> <?=surl?><?=site_root?>/members/check_twitter.php
</li>

<li><b>Default Access type:</b> Read & Write
</li>


</ul>
</div>



<p>
2) Click <b>Register application</b> and fill in the table:
</p>

<form method="post" action="change.php?title=Twitter">
<?
$sql="select activ,consumer_key,consumer_secret from users_qauth where title='Twitter'";
$rs->open($sql);
if(!$rs->eof)
{
?>
<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='twitter_activ'   <?if($rs->row["activ"]==1){echo("checked");}?>>
</div>

<div class='admin_field'>
<span>Consumer key:</span>
<input type='text' name='twitter_consumer_key'  style="width:400px" value="<?=$rs->row["consumer_key"]?>">
</div>

<div class='admin_field'>
<span>Consumer secret:</span>
<input type='text' name='twitter_consumer_secret'  style="width:400px" value="<?=$rs->row["consumer_secret"]?>">
</div>

<div class='admin_field'>
<input type='submit' class="btn btn-primary"  value="<?=word_lang("change")?>">
</div>
<?
}
?>
</form>

