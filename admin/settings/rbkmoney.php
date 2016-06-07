<?if(!defined("site_root")){exit();}?>

<p><a href="http://rbkmoney.ru/"><b>RBK Money</b></a> is Russian payments provider.</p>

<p>You should set:</p>

<ul>
<li><b>Postback Notification URL</b>:<br><?=surl.site_root."/members/payments_rbkmoney_go.php"?></li>

<li><b>Encryption:</b><br>MD5</li>

<li><b>Check signature:</b><br>Yes</li>
</ul>

<br>
<form method="post" action="rbkmoney_change.php">
<?
$sql="select * from gateway_rbkmoney";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Merchant ID:</span>
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



