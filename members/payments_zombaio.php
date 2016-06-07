<?if(!defined("site_root")){exit();}?>
<?
if($site_zombaio_account!="")
{
?>



<form method="post" name="process" id="process" action="https://secure.zombaio.com/?<?=$site_zombaio_account?>.<?=$site_zombaio_priceid?>.US">
<input type="hidden" name="identifier" value="<?=$product_id?>">
<input type="hidden" name="approve_url" value="<?=surl.site_root."/members/payments_result.php?d=1"?>">
<input type="hidden" name="decline_url" value="<?=surl.site_root."/members/payments_result.php?d=2"?>">
<input type="hidden" name="DynAmount_Value" value="<?=float_opt($product_total,2)?>">
<input type="hidden" name="DynAmount_Hash" value="<?=md5($site_zombaio_password.float_opt($product_total,2))?>">
<?
$sql="select quantity from credits_list where id_parent=".(int)$product_id;
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<input type="hidden" name="credit_value" value="<?=$rs->row["quantity"]?>">
	<?
}
?>
</form> 




<?
}
?>