<?if(!defined("site_root")){exit();}?>

<p><a href="http://webpay.by"><b>WebPay</b></a> is Belorussian payments provider.</p>



<br>
<form method="post" action="webpay_change.php">
<?
$sql="select * from gateway_webpay";
$rs->open($sql);
if(!$rs->eof)
{
?>

<div class='admin_field'>
<span><?=word_lang("login")?>:</span>
<input type='text' name='login'  style="width:400px" value="<?=$rs->row["login"]?>">
</div>

<div class='admin_field'>
<span><?=word_lang("password")?>:</span>
<input type='text' name='password2'  style="width:400px" value="<?=$rs->row["password2"]?>">
</div>

<div class='admin_field'>
<span>Merchant ID:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>


<div class='admin_field'>
<span>Security Key:</span>
<input type='text' name='password'  style="width:400px" value="<?=$rs->row["password"]?>">
</div>

<div class='admin_field'>
<span>Test mode:</span>
<input type='checkbox' name='test' value="1" <?if($rs->row["test"]==1){echo("checked");}?>>
</div>


<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='enable' value="1" <?if($rs->row["activ"]==1){echo("checked");}?>>
</div>




<?
}
?>
<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin-top:3px">
</form>



