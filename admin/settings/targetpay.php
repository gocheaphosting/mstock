<?if(!defined("site_root")){exit();}?>

<p><a href="http://targetpay.com"><b>Targetpay</b></a> is Ideal payments provider.</p>



<br>
<form method="post" action="targetpay_change.php">




<div class='admin_field'>
<span>Merchant ID:</span>
<input type='text' name='targetpay_account'  style="width:400px" value="<?=$global_settings["targetpay_account"]?>">
</div>



<div class='admin_field'>
<span>Test mode:</span>
<input type='checkbox' name='targetpay_test' value="1" <?if($global_settings["targetpay_test"]==1){echo("checked");}?>>
</div>


<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='targetpay_active' value="1" <?if($global_settings["targetpay_active"]==1){echo("checked");}?>>
</div>








<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin-top:3px">
</form>



