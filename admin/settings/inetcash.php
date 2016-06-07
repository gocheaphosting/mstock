<?if(!defined("site_root")){exit();}?>

<p><a href="http://www.inet-cash.com"><b>InetCash</b></a> is a payments provider.</p>

<p>You have to set <b>SHOP URL</b> in your member area at inet-cash.com:<br>
<b><?=surl.site_root."/members/payments_inetcash.php"?></b>

</p>

<p>We recommend you to rename the file for security reasons.</p>



<br>
<form method="post" action="inetcash_change.php">
<?
$sql="select * from gateway_inetcash";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Site ID:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
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



