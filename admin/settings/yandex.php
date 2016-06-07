<?if(!defined("site_root")){exit();}?>

<p><a href="http://money.yandex.ru"><b>Yandex.money</b></a> is Russian payments provider.</p>

<p>Вам нужно зарегистрироваться на  сайте <a href="https://kassa.yandex.ru/">kassa.yandex.ru</a> и пройти процедуру проверки.</p>


<p>Настроить:</p>
<ul>

<li>
	<b>Способ подключения к кассе:</b>
	<br>HTTP-протокол
</li>

<li>
	<b>checkURL и avisoURL:</b>
	<br><?=surl.site_root."/members/payments_yandex_go.php"?>
</li>



<li>
	<b>Success URL:</b>
	<br><?=surl.site_root."/members/payments_result.php?d=1"?>
</li>

<li>
	<b>Fail URL:</b>
	<br><?=surl.site_root."/members/payments_result.php?d=2"?>
</li>
</ul>

<br>
<form method="post" action="yandex_change.php">




<div class='admin_field'>
<span>Идентификатор контрагента (Shop ID):</span>
<input type='text' name='yandex_account'  style="width:400px" value="<?=$global_settings["yandex_account"]?>">
</div>

<div class='admin_field'>
<span>Номер витрины контрагента:</span>
<input type='text' name='yandex_account2'  style="width:400px" value="<?=$global_settings["yandex_account2"]?>">
</div>

<div class='admin_field'>
<span>Пароль магазина:</span>
<input type='text' name='yandex_password'  style="width:400px" value="<?=$global_settings["yandex_password"]?>">
</div>



<div class='admin_field'>
<span>Режим тестирования:</span>
<input type='checkbox' name='yandex_test' value="1" <?if($global_settings["yandex_test"]==1){echo("checked");}?>>
</div>


<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='yandex_active' value="1" <?if($global_settings["yandex_active"]==1){echo("checked");}?>>
</div>








<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin-top:3px">
</form>



