<?if(!defined("site_root")){exit();}?>

<p>You should login on cashx.com as merchant and go to
Merchants -> Shopping Cart -> IPN Settings and set:</p>

<ul>
<li><b>IPN Status:</b> Enabled</li>
<li><b>Notify URL:</b> <?=surl.site_root."/members/payments_process.php?mode=notification&processor=cashx"?></li>
<li><b>Security Code</b></li>
</ul>





<form method="post" action="cashx_change.php">
<?
$sql="select * from gateway_cashx";
$rs->open($sql);
if(!$rs->eof)
{
?>



<div class='admin_field'>
<span><?=word_lang("account")?>:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>Security code:</span>
<input type='text' name='security_code'  style="width:400px" value="<?=$rs->row["security_code"]?>">
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



