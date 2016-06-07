<?if(!defined("site_root")){exit();}?>

<p><a href="http://www.payumoney.com"><b>PayUMoney</b></a> is a payments gateway provider.</p>



<br>
<form method="post" action="payumoney_change.php">




<div class='admin_field'>
<span>Merchant key:</span>
<input type='text' name='payumoney_account'  style="width:400px" value="<?=$global_settings["payumoney_account"]?>">
</div>

<div class='admin_field'>
<span>Merchant Salt:</span>
<input type='text' name='payumoney_password'  style="width:400px" value="<?=$global_settings["payumoney_password"]?>">
</div>




<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='payumoney_active' value="1" <?if($global_settings["payumoney_active"]==1){echo("checked");}?>>
</div>



<div class='admin_field'>
<span>Test mode:</span>
<input type='checkbox' name='payumoney_test' value="1" <?if($global_settings["payumoney_test"]==1){echo("checked");}?>>
</div>





<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin-top:3px">
</form>



