<?if(!defined("site_root")){exit();}?>

<p><a href="http://goemerchant.com"><b>GoEMerchant</b></a> is a payments provider.</p>



<br>
<form method="post" action="goemerchant_change.php">
<?
$sql="select * from gateway_goemerchant";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Transaction Center number:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>Gateway ID:</span>
<input type='text' name='account2'  style="width:400px" value="<?=$rs->row["account2"]?>">
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



