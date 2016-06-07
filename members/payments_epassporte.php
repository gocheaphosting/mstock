<?if(!defined("site_root")){exit();}?>
<?
if($site_epassporte_account!=""){

if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
header("header:payments_result.php?d=1");

}
else
{
?>

<form method="post" action="<?=epassporte_url?>"  name="process" id="process">
<input type="hidden" name="acct_num" value="<?=$site_epassporte_account?>">
<input type="hidden" name="pi_code" value="<?=$site_epassporte_code?>">
<input type="hidden" name="amount" value="<?=$product_total?>">

<input type="hidden" name="return_url" value="<?=surl?><?=site_root?>/members/payments_result.php?d=1">
<input type="hidden" name="response_post" value="<?=surl?><?=site_root?>/members/payments_result.php?d=1">
<input type="hidden" name="product_name" value="<?=$product_id?>">
<input type="hidden" name="tax_amount" value="0.00">
<input type="hidden" name="shipping_amount" value="0.00">

</form>

<?
}
}
?>
