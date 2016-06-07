<?if(!defined("site_root")){exit();}?>

<p>Verify your <b>Paypal</b> account status. Go to your <b>PayPal Profile</b> under <b>My settings</b> and verify that your <b>Account Type</b> is either <b>Premier</b> or <b>Business</b>, or upgrade your account.</p>

<p>Verify your <b>API settings</b>. Click on <b>My selling</b> tools. Click <b>Selling online</b> and verify your <b>API access</b>. Click <b>Update</b> to view or set up your API signature and credentials.</p>

<form method="post" action="paypalpro_change.php">

<?
$sql="select * from gateway_paypalpro";
$rs->open($sql);
if(!$rs->eof)
{
?>
<br>


<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='enable' value="1" <?if($rs->row["activ"]==1){echo("checked");}?>>
</div>


<div class='admin_field'>
<span>Username:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>



<div class='admin_field'>
<span>Password:</span>
<input type='text' name='password'  style="width:400px" value="<?=$rs->row["password"]?>">
</div>



<div class='admin_field'>
<span>Signature:</span>
<input type='text' name='signature'  style="width:400px" value="<?=$rs->row["signature"]?>">
</div>






<div class='admin_field'>
<span><?=word_lang("allow ipn")?>:</span>
<input type='checkbox' name='ipn' value="1" <?if($rs->row["ipn"]==1){echo("checked");}?>>
</div>


<?
}
?>
<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>" style="margin-top:3px">
</form>