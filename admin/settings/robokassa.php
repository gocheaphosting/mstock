<?if(!defined("site_root")){exit();}?>

<p><a href="http://robokassa.ru/"><b>Robokassa</b></a> is Russian payments provider.</p>

<p>You should set:</p>
<ul>
<li>
	<b>Result URL:</b>
	<br><?=surl.site_root."/members/payments_robokassa_go.php"?>
</li>

<li>
	<b>Send method:</b>
	<br>GET
</li>

<li>
	<b>Success URL:</b>
	<br><?=surl.site_root."/members/payments_result.php?d=1"?>
</li>

<li>
	<b>Fail URL:</b>
	<br><?=surl.site_root."/members/payments_result.php?d=2"?>
</li>
</ul>

<br>
<form method="post" action="robokassa_change.php">
<?
$sql="select * from gateway_robokassa";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Merchant login:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>Password #1:</span>
<input type='text' name='password'  style="width:400px" value="<?=$rs->row["password"]?>">
</div>

<div class='admin_field'>
<span>Password #2:</span>
<input type='text' name='password2'  style="width:400px" value="<?=$rs->row["password2"]?>">
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



