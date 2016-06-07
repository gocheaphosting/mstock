<?if(!defined("site_root")){exit();}?>
<?
if($site_linkpoint_account!=""){
if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
header("header:payments_result.php?d=1");

}
else
{
?>
<form method="post" action="<?=linkpoint_url?>"  name="process" id="process"> 
	<input type="hidden" name="responseURL" value="">
	

	<input type="hidden" name="storename" value="<?=$site_linkpoint_account?>">
	<input type="hidden" name="chargetotal" value="<?=$product_total?>">
	<input type="hidden" name="txnorg" value="eci">
	<input type="hidden" name="mode" value="fullpay">
	<input type="hidden" name="txntype" value="sale">	
	<input type="hidden" name="oid" value="<?=$product_id?>">

<?
$sql="select name,email,telephone,address,country,city from users where id_parent=".(int)$_SESSION["people_id"];
$dr->open($sql);
if(!$dr->eof)
{
?>
	<input type="hidden" name="bname" value="<?=$dr->row["name"]?>">
	<input type="hidden" name="baddr1" value="<?=$dr->row["address"]?>">
	<input type="hidden" name="baddr2" value="">
	<input type="hidden" name="bcity" value="">
	<input type="hidden" name="bstate" value="">
	<input type="hidden" name="bcountry" value="<?=$dr->row["country"]?>">
	<input type="hidden" name="bzip" value="">
	<input type="hidden" name="saddr1" value="<?=$dr->row["address"]?>">
	<input type="hidden" name="saddr2" value="">
	<input type="hidden" name="scity" value="<?=$dr->row["city"]?>">
	<input type="hidden" name="sstate" value="">
	<input type="hidden" name="scountry" value="<?=$dr->row["country"]?>">
	<input type="hidden" name="bzip" value="">
	<input type="hidden" name="phone" value="<?=$dr->row["telephone"]?>">
	<input type="hidden" name="fax" value="">
	<input type="hidden" name="email" value="<?=$dr->row["email"]?>">
<?
}
?>
</form>

<?
}
}
?>
