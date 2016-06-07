<?if(!defined("site_root")){exit();}?>

<p><a href="http://ccavenue.com/"><b>CCAvenue</b></a> is a payments provider.</p>



<br>
<form method="post" action="ccavenue_change.php">
<?
$sql="select * from gateway_ccavenue";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Merchant ID:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>Access Code:</span>
<input type='text' name='password2'  style="width:400px" value="<?=$rs->row["password2"]?>">
</div>

<div class='admin_field'>
<span>Encryption Key:</span>
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



