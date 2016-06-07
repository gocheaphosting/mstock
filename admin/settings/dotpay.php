<?if(!defined("site_root")){exit();}?>

<p><a href="http://dotpay.pl"><b>DotPay.pl</b></a> is a payments provider.</p>

<p>You should set the <b>URLC</b> in <b>Settings -> URLC parameters</b> at Dotpay.pl:<br>
<b><?=surl.site_root."/members/payments_dotpay_go.php"?></b>
</p>

<p>Or enable:<br><b>Permits to receive
URLC parameter from external services</b></p>

<br>
<form method="post" action="dotpay_change.php">
<?
$sql="select * from gateway_dotpay";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Account ID:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>Secret key:</span>
<input type='text' name='password'  style="width:400px" value="<?=$rs->row["password"]?>">
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



