<?if(!defined("site_root")){exit();}?>

<p><a href="http://transferuj.pl"><b>Transferuj</b></a> is Polish payments provider.</p>



<br>
<form method="post" action="transferuj_change.php">




<div class='admin_field'>
<span>Merchant ID:</span>
<input type='text' name='transferuj_account'  style="width:400px" value="<?=$global_settings["transferuj_account"]?>">
</div>

<div class='admin_field'>
<span>Secret word:</span>
<input type='text' name='transferuj_password'  style="width:400px" value="<?=$global_settings["transferuj_password"]?>">
</div>




<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='transferuj_active' value="1" <?if($global_settings["transferuj_active"]==1){echo("checked");}?>>
</div>








<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin-top:3px">
</form>



