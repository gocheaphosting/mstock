<?if(!defined("site_root")){exit();}?>

<p><a href="http://www.fortumo.com"><b>Fortumo</b></a> is a mobile payments provider.</p>

<p>You may pay <b>only for the Credits</b> by Fortumo. It is impossible to purchase orders in dollars or subscription plans.</p>

<p>First you have to create a <b>new account</b> on Fortumo and add a <b>	Pay-by-Mobile Widget</b>.</p>

<ul>
<li><b>Name of the credit:</b> Credits</li>
<li><b>The selling rate of the credit.</b> You havee to find an appropriate price. You should notice that the credits packages won't correspond to the <a href="../credits_types/">settings</a></li>

<li><b>To which URL will your payment requests be forwarded to?</b><br>
<?=surl.site_root."/members/payments_fortumo.php"?>
</li>

<li><b>Where to redirect the user after completing the payment?</b><br>
<?=surl.site_root."/members/payments_result.php?d=1"?>
</li>

</ul>

<br>
<form method="post" action="fortumo_change.php">
<?
$sql="select * from gateway_fortumo";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Service ID:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>Secure Key:</span>
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



