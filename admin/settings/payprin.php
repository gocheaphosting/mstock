<?if(!defined("site_root")){exit();}?>

<p><b>PayPrin AxisGwy</b> is a payments provider.</p>

<p>You should set:</p>

<ul>
<li><b>Postback Notification URL</b>:<br><?=surl.site_root."/members/payments_payprin_go.php"?></li>


</ul>

<br>
<form method="post" action="payprin_change.php">
<?
$sql="select * from gateway_payprin";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Source key:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>PIN:</span>
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



