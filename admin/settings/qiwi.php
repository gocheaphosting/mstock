<?if(!defined("site_root")){exit();}?>

<p>
You should login in your QIWI account at <a href="http://ishop.qiwi.ru/"><b>ishop.qiwi.ru</b></a> and enable <b>HTTP</b> and <b>SOAP</b> protocols.
</p>

<p>
<b>SOAP protocol -> Settings -> URL (callback):</b><br>
<a href="<?=surl?><?=site_root?>/members/payments_process.php?mode=notification&processor=qiwi"><?=surl?><?=site_root?>/members/payments_process.php?mode=notification&processor=qiwi</a></p>
</p>


<p>
<b>Disable WSS signature</b><br>

</p>
<br>

<form method="post" action="qiwi_change.php">
<?
$sql="select * from gateway_qiwi";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Account ID:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span><?=word_lang("password")?>:</span>
<input type='text' name='password'  style="width:400px" value="<?=$rs->row["password"]?>">
</div>

<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='enable' value="1" <?if($rs->row["activ"]==1){echo("checked");}?>>
</div>

<div class='admin_field'>
<span>Approve orders automatically:</span>
<input type='checkbox' name='ipn' value="1" <?if($rs->row["ipn"]==1){echo("checked");}?>>
</div>


<?
}
?>
<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin-top:3px">
</form>



