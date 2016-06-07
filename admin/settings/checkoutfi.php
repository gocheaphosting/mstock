<?if(!defined("site_root")){exit();}?>

<p><a href="http://www.checkout.fi"><b>Checkout.fi</b></a> is a Finnish payments gateway provider.</p>



<br>
<form method="post" action="checkoutfi_change.php">




<div class='admin_field'>
<span>Merchant ID:</span>
<input type='text' name='checkoutfi_account'  style="width:400px" value="<?=$global_settings["checkoutfi_account"]?>">
</div>

<div class='admin_field'>
<span>Security Key:</span>
<input type='text' name='checkoutfi_password'  style="width:400px" value="<?=$global_settings["checkoutfi_password"]?>">
</div>




<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='checkoutfi_active' value="1" <?if($global_settings["checkoutfi_active"]==1){echo("checked");}?>>
</div>








<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin-top:3px">
</form>



