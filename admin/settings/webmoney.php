<?if(!defined("site_root")){exit();}?>

<p>You have to login at Webmoney site, go to 'Settings' section and set the parameters:</p>

<ul>
	<li>
		<b>Result URL:</b><br>
		<?=surl.site_root."/members/payments_webmoney_go.php"?>
	</li>
	
	<li>
		<b>Success URL:</b><br>
		<?=surl.site_root."/members/payments_result.php?d=1"?>
	</li>
	
	<li>
		<b>Fail URL:</b><br>
		<?=surl.site_root."/members/payments_result.php?d=2"?>
	</li>

<li><b>Hash-method:</b> SHA256</li>

</ul>


<form method="post" action="webmoney_change.php">
<?
$sql="select * from gateway_webmoney";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Merchant ID:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>Security code:</span>
<input type='text' name='ecode'  style="width:400px" value="<?=$rs->row["ecode"]?>">
</div>

<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='enable' value="1" <?if($rs->row["activ"]==1){echo("checked");}?>>
</div>

<div class='admin_field'>
<span><?=word_lang("allow ipn")?>:</span>
<input type='checkbox' name='ipn' value="1" <?if($rs->row["ipn"]==1){echo("checked");}?>>
</div>


<?
}
?>
<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin-top:3px">
</form>

