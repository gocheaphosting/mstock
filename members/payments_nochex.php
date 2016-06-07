<?if(!defined("site_root")){exit();}?>
<?
if($site_nochex_account!=""){
if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
header("header:payments_result.php?d=1");

}
else
{
?>

<form method="post" action="<?=nochex_url?>"  name="process" id="process">
<input type="hidden" name="merchant_id" value="<?=$site_nochex_account?>" />
<input type="hidden" name="amount" value="<?=$product_total?>" />
<input type="hidden" name="status" value="live" />
<input type="hidden" name="description" value="<?=$global_settings["site_name"]?>" />
<input type="hidden" name="order_id" value="<?=$product_id?>" />
<input type="hidden" name="success_url" value="<?=surl?><?=site_root?>/members/payments_result.php?d=1" />
<input type="hidden" name="cancel_url" value="<?=surl?><?=site_root?>/members/payments.php?d=1" />


<?
$sql="select name,email,telephone,address,country from users where id_parent=".(int)$_SESSION["people_id"];
$dr->open($sql);
if(!$dr->eof)
{
?>	
<input type="hidden" name="billing_fullname" value="<?=$dr->row["name"]?>" />
<input type="hidden" name="billing_address" value="<?=$dr->row["address"]?>" />
<input type="hidden" name="billing_postcode" value="" />
<input type="hidden" name="delivery_fullname" value="<?=$dr->row["name"]?>" />
<input type="hidden" name="delivery_address" value="<?=$dr->row["address"]?>" />
<input type="hidden" name="delivery_postcode" value="" />
<input type="hidden" name="email_address" value="<?=$dr->row["email"]?>" />
<input type="hidden" name="customer_phone_number" value="<?=$dr->row["telephone"]?>" />
<?
}
?>

</form>

<?
}
}
?>
