<?if(!defined("site_root")){exit();}?>

<p><a href="http://gopay.cz"><b>GoPay</b></a> is a Czech payments provider.</p>



<br>
<form method="post" action="gopay_change.php">
<?
$sql="select * from gateway_gopay";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>GoID:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>Secure Key:</span>
<input type='text' name='password'  style="width:400px" value="<?=$rs->row["password"]?>">
</div>


<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='enable' value="1" <?if($rs->row["activ"]==1){echo("checked");}?>>
</div>

<div class='admin_field'>
<span>Test regime:</span>
<input type='checkbox' name='test' value="1" <?if($rs->row["test"]==1){echo("checked");}?>>
</div>




<?
}
?>
<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin-top:3px">
</form>



