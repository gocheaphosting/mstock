<?if(!defined("site_root")){exit();}?>

<p><a href="http://www.verotel.com"><b>Verotel</b></a> is a payment gateway provider.</p>

<ul>
<li>
	<b>Post Back URL:</b>
	<br><?=surl.site_root."/members/payments_verotel_go.php"?>
</li>



<li>
	<b>Successful URL:</b>
	<br><?=surl.site_root."/members/payments_result.php?d=1"?>
</li>


</ul>

<br>
<form method="post" action="verotel_change.php">




<div class='admin_field'>
<span>Shop ID:</span>
<input type='text' name='verotel_account'  style="width:400px" value="<?=$global_settings["verotel_account"]?>">
</div>

<div class='admin_field'>
<span>Signature Key:</span>
<input type='text' name='verotel_password'  style="width:400px" value="<?=$global_settings["verotel_password"]?>">
</div>




<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='verotel_active' value="1" <?if($global_settings["verotel_active"]==1){echo("checked");}?>>
</div>








<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin-top:3px">
</form>



