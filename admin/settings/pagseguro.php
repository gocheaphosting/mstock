<?if(!defined("site_root")){exit();}?>

<p><a href="http://pagseguro.com.br/"><b>PagSeguro</b></a> is Brazilian payments provider.</p>

<p>You should set the <b>Postback Notification URL</b> in your member area of PagSeguro:<br><b><?=surl.site_root."/members/payments_pagseguro_go.php"?></b></p>


<br>
<form method="post" action="pagseguro_change.php">
<?
$sql="select * from gateway_pagseguro";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Seller Email:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>Token:</span>
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



