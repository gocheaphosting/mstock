<?if(!defined("site_root")){exit();}?>
<?
if($site_worldpay_account!=""){
if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
header("header:payments_result.php?d=1");

}
else
{
?>
<form method="post" action="<?=worldpay_url?>" name="process" id="process"> 
	 <input type=hidden name=instId value="<?=$site_worldpay_account?>">
	 <input type=hidden name=cartId value="<?=$product_id?>">
	 <input type=hidden name=amount value="<?=$product_total?>">
	 <input type=hidden name=currency value="<?=$currency_code1?>">
	 <input type=hidden name=testMode value="N">

<?
$sql="select name,email,telephone,address,country from users where id_parent=".(int)$_SESSION["people_id"];
$dr->open($sql);
if(!$dr->eof)
{
?>
<input type=hidden name=name value="<?=$dr->row["name"]?>">
<input type=hidden name=tel value="<?=$dr->row["telephone"]?>">
<input type=hidden name=email value="<?=$dr->row["email"]?>">
<input type=hidden name=address value="<?=$dr->row["address"]?>">
<input type=hidden name=country value="<?=$dr->row["country"]?>">
<?
}
?>

	 <input type=hidden name=MC_csid value="<?=$product_id?>">

</form>

<?
}
}
?>
