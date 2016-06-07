<?if(!defined("site_root")){exit();}?>

<p><a href="http://privatbank.ua"><b>PrivatBank.ua</b></a> is a payments provider.</p>



<br>
<form method="post" action="privatbank_change.php">
<?
$sql="select * from gateway_privatbank";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Member ID:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>Secret key:</span>
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



