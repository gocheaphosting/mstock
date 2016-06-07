<?if(!defined("site_root")){exit();}?>

<p>Please login on <a href="http://www.paypal.com/">www.paypal.com</a> as merchant</p>
<p>Enable <b>"Instant Payment Notification"</p>


<p>Set <b>Notify URL:</b><br> <?=surl.site_root."/members/payments_process.php?mode=notification&processor=paypal"?></p>

<form method="post" action="paypal_change.php">
<?
$sql="select * from gateway_paypal";
$rs->open($sql);
if(!$rs->eof)
{
?>



<div class='admin_field'>
<span><?=word_lang("account")?>:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
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