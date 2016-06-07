<?if(!defined("site_root")){exit();}?>

<p><a href="http://www.dwolla.com/"><b>Dwolla</b></a> is a payments provider. 
The best way to move money. No percentages. No hidden fees. Just 25 cents per transaction or free for transactions $10 and less.
</p>


<ul>
<li>You should login Dwolla account and go to App permissions -> Create an application.</li>


<li><b>Payment Callback URL</b> and <b>
Payment Redirect URL </b>:<br><?=surl.site_root."/members/payments_dwolla_go.php"?></li>



</ul>

<br>
<form method="post" action="dwolla_change.php">
<?
$sql="select * from gateway_dwolla";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Account:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>API Key:</span>
<input type='text' name='apikey'  style="width:400px" value="<?=$rs->row["apikey"]?>">
</div>

<div class='admin_field'>
<span>API Secret:</span>
<input type='text' name='apisecret'  style="width:400px" value="<?=$rs->row["apisecret"]?>">
</div>

<div class='admin_field'>
<span>Pin:</span>
<input type='text' name='pin'  style="width:400px" value="<?=$rs->row["pin"]?>">
</div>

<div class='admin_field'>
<span>Test mode:</span>
<input type='checkbox' name='enable' value="1" <?if($rs->row["test"]==1){echo("checked");}?>>
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



