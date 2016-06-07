<?if(!defined("site_root")){exit();}?>

<p><b>Please notice that <a href="http://www.2checkout.com/">2checkout.com</a> doesn't allow Credits model!</b></p>

<p>You should do the next steps first:<br>

- Log in to your 2Checkout account<br>
- Click on the <b>Look & Feel settings</b>.<br>
- Set the option <b>Direct Return?</b> to <b>Yes</b><br>
- Set the <b>Approved URL</b> to:<br>
<a href="<?=surl?><?=site_root?>/members/payments_process.php?mode=notification&product_type=order&processor=2checkout"><?=surl?><?=site_root?>/members/payments_process.php?mode=notification&product_type=order&processor=2checkout</a><br>
- Set the <b>Pending URL</b> to:<br>
<a href="<?=surl?><?=site_root?>/members/payments_process.php?mode=notification&product_type=order&processor=2checkout"><?=surl?><?=site_root?>/members/payments_process.php?mode=notification&product_type=order&processor=2checkout</a><br>

</p>


<form method="post" action="2checkout_change.php">
<?
$sql="select * from gateway_2checkout";
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