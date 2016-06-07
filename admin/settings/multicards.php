<?if(!defined("site_root")){exit();}?>

<p>Please, login into multicards.com merchant account</p>

<p>
Go to Orderpages -> Page (ID = Order page ID)  and enable Silent Post:
</p>

<p>
<b>Successful transaction return location (PostURL):</b><br>
<?=surl.site_root."/members/payments_process.php?mode=notification&processor=multicards"?>
</p>

<p>
<b>Post Fields:</b>  
    mer_id,item1_price,item1_qty,item1_desc,user1
</p>





<form method="post" action="multicards_change.php">
<?
$sql="select * from gateway_multicards";
$rs->open($sql);
if(!$rs->eof)
{
?>


<div class='admin_field'>
<span><?=word_lang("account")?>:</span>
<input type='text' name='account'  style="width:400px" value="<?=$rs->row["account"]?>">
</div>

<div class='admin_field'>
<span>Order page ID:</span>
<input type='text' name='account2'  style="width:400px" value="<?=$rs->row["account2"]?>">
</div>

<div class='admin_field'>
<span>Silent Post Password:</span>
<input type='text' name='password'  style="width:400px" value="<?=$rs->row["password"]?>">
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






