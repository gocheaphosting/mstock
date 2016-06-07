<?if(!defined("site_root")){exit();}?>

<p><a href="http://payson.se"><b>Payson</b></a> is Swedish payments provider.</p>



<br>
<form method="post" action="payson_change.php">
<?
$sql="select * from gateway_payson";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Receiver Email:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>


<div class='admin_field'>
<span>Agent ID:</span>
<input type='text' name='account2'  style="width:400px" value="<?=$rs->row["account2"]?>">
</div>


<div class='admin_field'>
<span>Security Key:</span>
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



