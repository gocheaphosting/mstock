<?if(!defined("site_root")){exit();}?>

<p>You should login in your account at <a href="http://www.myvirtualmerchant.com/"><b>myvirtualmerchant.com</b></a> and fill in the settings:</p>
<br>
<form method="post" action="myvirtualmerchant_change.php">
<?
$sql="select * from gateway_myvirtualmerchant";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Merchant ID:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>User ID:</span>
<input type='text' name='account2'  style="width:400px" value="<?=$rs->row["account2"]?>">
</div>

<div class='admin_field'>
<span>PIN:</span>
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



