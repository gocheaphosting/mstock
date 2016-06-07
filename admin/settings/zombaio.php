<?if(!defined("site_root")){exit();}?>

<p><a href="http://www.zombaio.com"><b>Zombaio.com</b></a> is an Adult Entertainment Industry payments provider.</p>



<p>You may pay <b>only for the Credits</b> by Zombaio.</p>

<ul>
<li>First you have to create a <b>new account</b> on <a href="https://secure.zombaio.com/zoa">Zombaio.com</a> and add a <b>New Site (manually)</b>.</li>

<li>Go to <b>Pricing Structure</b> and add <b>New pricing</b>. Pricing type: <b>Purchase of Credits</b>. </li>

<li>You must have <a href="../credits_types/"><b>the same credits list</b></a> on the site.</li>

<li><b>Postback URL:</b><br><?=surl.site_root."/members/payments_zombaio_go.php"?></li>


</ul>

<br>
<form method="post" action="zombaio_change.php">
<?
$sql="select * from gateway_zombaio";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span>Site ID:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>Zombaio GW Pass:</span>
<input type='text' name='password'  style="width:400px" value="<?=$rs->row["password"]?>">
</div>

<div class='admin_field'>
<span>Pricing Structure ID:</span>
<input type='text' name='price'  style="width:400px" value="<?=$rs->row["price"]?>">
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



