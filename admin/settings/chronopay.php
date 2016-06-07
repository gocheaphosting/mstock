<?if(!defined("site_root")){exit();}?>

<p>In the Chronopay client interface please set the <b>Access URL</b> field of the product you will use to the following:<br> 
<a href="<?=surl?><?=site_root?>/members/payments_process.php?mode=notification&product_type=order&processor=chronopay"><?=surl?><?=site_root?>/members/payments_process.php?mode=notification&product_type=order&processor=chronopay</a></p>


<form method="post" action="chronopay_change.php">
<?
$sql="select * from gateway_chronopay";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span><?=word_lang("product id")?>:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span><?=word_lang("encryption key")?>:</span>
<input type='text' name='ekey'  style="width:400px" value="<?=$rs->row["ekey"]?>">
</div>

<div class='admin_field'>
<span><?=word_lang("enable")?>:</span>
<input type='checkbox' name='enable' value="1" <?if($rs->row["activ"]==1){echo("checked");}?>>
</div>

<div class='admin_field'>
<span><?=word_lang("allow ipn")?>:</span>
<input type='checkbox' name='ipn' value="1" <?if($rs->row["ipn"]==1){echo("checked");}?>>
</div>


<?
}
?>
<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin-top:3px">
</form>



