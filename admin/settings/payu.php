<?if(!defined("site_root")){exit();}?>

<p><a href="http://payu.pl"><b>PayU</b></a> is a payments provider.</p>

<p>You should set the parameter in your account at payu.com:</p>

<ul>
<li>
	<b>Adres raportow:</b><br>
	<?=surl.site_root."/members/payments_payu_go.php"?>
</li>

<li>
	<b>Adres powrotu pozytywnego:</b>
	<br><?=surl.site_root."/members/payments_result.php?d=1"?>
</li>

<li>
	<b>Adres powrotu blednego:</b>
	<br><?=surl.site_root."/members/payments_result.php?d=2"?>
</li>
</ul>

<br>
<form method="post" action="payu_change.php">
<?
$sql="select * from gateway_payu";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Id punktu platnosci (pos_id):</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>Klucz (MD5):</span>
<input type='text' name='password'  style="width:400px" value="<?=$rs->row["password"]?>">
</div>

<div class='admin_field'>
<span>Drugi klucz (MD5):</span>
<input type='text' name='password2'  style="width:400px" value="<?=$rs->row["password2"]?>">
</div>


<div class='admin_field'>
<span>Klucz autoryzacji platnosci (pos_auth_key):</span>
<input type='text' name='password3'  style="width:400px" value="<?=$rs->row["password3"]?>">
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



