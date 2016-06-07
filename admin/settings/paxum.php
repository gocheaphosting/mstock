<?if(!defined("site_root")){exit();}?>

<p><a href="http://paxum.com"><b>Paxum.com</b></a> is a payments provider.</p>

<p>To start receiving IPN messages you have to login into your account, go to Merchant Services -> IPN Settings, select "Turn ON IPN", enter notification URL and select "Receive IPN messages":</p>

<p><b>Notification URL:</b><br>
<?=surl.site_root."/members/payments_paxum_go.php"?>
</p>


<br>
<form method="post" action="paxum_change.php">
<?
$sql="select * from gateway_paxum";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Account:</span>
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



