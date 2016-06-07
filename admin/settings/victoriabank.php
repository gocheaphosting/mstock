<?if(!defined("site_root")){exit();}?>

<p><a href="http://vb.md"><b>Victoria</b></a> is Moldavian bank.</p>

<p>
You should set the parameters:
</p>

<ul>

<li>Upload <b>Public key</b> here on ftp:<br> <b><?=site_root?>/admin/plugins/victoriabank/victoria_pub.pem</b></li>
<br>
<li>Generate a <b>new RSA key </b> in the command string on your computer:<br> <b>openssl genrsa -f4 -out key.pem 2048 </b></li>
<br>
<li>Upload <b>RSA key</b> here on ftp:<br> <b><?=site_root?>/admin/plugins/victoriabank/key.pem</b></li>
<br>
<li>Set the <b>Call back URL</b> in your account at vb.mb:<br> <b><?=surl.site_root."/members/payments_victoriabank_go.php"?></b></li>

</ul>

<br>
<form method="post" action="victoriabank_change.php">
<?
$sql="select * from gateway_victoriabank";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Terminal:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>


<div class='admin_field'>
<span>Merchant:</span>
<input type='text' name='account2'  style="width:400px" value="<?=$rs->row["account2"]?>">
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



