<?if(!defined("site_root")){exit();}?>

<p>Login into Google Checkout as a seller,<br> 
go to <b>My Sales -> Settings -> Integration</b><br>
and set <b>API callback URL:</b><br>
<b><?=ssurl.site_root."/members/payments_process.php?mode=notification&processor=google"?></b><br>
Your web service must be secured by SSL v3 or TLS and must use a valid SSL certificate (https).
</p>



<form method="post" action="google_change.php">
<?
$sql="select * from gateway_google";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Merchant ID:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>Merchant Key:</span>
<input type='text' name='mkey'  style="width:400px" value="<?=$rs->row["mkey"]?>">
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

