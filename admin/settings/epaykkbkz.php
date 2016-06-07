<?if(!defined("site_root")){exit();}?>

<p><a href="http://epay.kkb.kz/"><b>Epay.kkb.kz</b></a> is a payments provider of Kazakhstan.</p>

<p>To install the gateway you should:</p>

<ul>
	<li>Modify the file on ftp: <?=site_root?>/admin/plugins/epaykkbkz/<b>config.txt</b></li>
	<li>Upload <b>Private and Public certificate files</b> here: <?=site_root?>/admin/plugins/epaykkbkz/</li>
</ul>

<br>
<form method="post" action="epaykkbkz_change.php">
<?
$sql="select * from gateway_epaykkbkz";
$rs->open($sql);
if(!$rs->eof)
{
?>




<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='enable' value="1" <?if($rs->row["activ"]==1){echo("checked");}?>>
</div>




<?
}
?>
<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin-top:3px">
</form>