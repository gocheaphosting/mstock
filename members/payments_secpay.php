<?if(!defined("site_root")){exit();}?>
<?
if($site_secpay_account!=""){
if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
header("header:payments_result.php?d=1");

}
else
{



$md5_hash= md5($product_id . $product_total . $site_secpay_password);
?>
  <form action="<?=secpay_url?>" method="POST"  name="process" id="process">
	<input type="hidden" name="merchant" value="<?=$site_secpay_account?>">
	<input type="hidden" name="trans_id" value="<?=$product_id?>">
	<input type="hidden" name="amount" value="<?=$product_total?>">
	<input type="hidden" name="callback" value="<?=surl?><?=site_root?>/members/payments.php?id=<?=$_GET["id"]?>">
	<input type="hidden" name="currency" value="<?=$currency_code1?>">
	<input type="hidden" name="options" value="test_status=live,cb_post=true,dups=false,md_flds=trans_id:amount:callback">
	<input type="hidden" name="deferred" value="full">
	<input type="hidden" name="mail_subject" value="<?=$site_secpay_subject?>">
	<input type="hidden" name="mail_message" value="<?=$site_secpay_message?>">

<input type="hidden" name="digest" value="<?=$md5_hash?>">


<?
$sql="select name,email,telephone,address,country,city from users where id_parent=".(int)$_SESSION["people_id"];
$dr->open($sql);
if(!$dr->eof)
{
?>
	<input type="hidden" name="bill_name" value="<?=$dr->row["name"]?>">
	<input type="hidden" name="bill_company" value="">
	<input type="hidden" name="bill_addr_1" value="<?=$dr->row["address"]?>">
	<input type="hidden" name="bill_addr_2" value="">
	<input type="hidden" name="bill_city" value="">
	<input type="hidden" name="bill_state" value="">
	<input type="hidden" name="bill_country" value="<?=$dr->row["country"]?>">
	<input type="hidden" name="bill_post_code" value="">
	<input type="hidden" name="bill_tel" value="<?=$dr->row["telephone"]?>">
	<input type="hidden" name="bill_email" value="<?=$dr->row["email"]?>">
	<input type="hidden" name="bill_url" value="">

	<input type="hidden" name="ship_name" value="<?=$dr->row["name"]?>">
	<input type="hidden" name="ship_company" value="">
	<input type="hidden" name="ship_addr_1" value="<?=$dr->row["address"]?>">
	<input type="hidden" name="ship_addr_2" value="">
	<input type="hidden" name="ship_city" value="<?=$dr->row["city"]?>">
	<input type="hidden" name="ship_state" value="">
	<input type="hidden" name="ship_country" value="<?=$dr->row["country"]?>">
	<input type="hidden" name="ship_post_code" value="">
	<input type="hidden" name="ship_tel" value="<?=$dr->row["telephone"]?>">
	<input type="hidden" name="ship_email" value="<?=$dr->row["email"]?>">
	<input type="hidden" name="ship_url" value="">
<?
}
?>

</form>

<?
}
}
?>
