<?if(!defined("site_root")){exit();}?>
<?
if($site_payu_account!="")
{
	?>
	<form action="https://secure.payu.com/paygw/UTF/NewPayment" method="POST" name="process" id="process">
	<input type="hidden" name="first_name" value="<?=$_SESSION["people_name"]?>">
	<input type="hidden" name="email" value="<?=$_SESSION["people_email"]?>">
	<?
	$sql="select lastname from users where id_parent=".(int)$_SESSION["people_id"];
	$ds->open($sql);
	if(!$ds->eof)
	{
		?>
			<input type="hidden" name="last_name" value="<?=$ds->row["lastname"]?>">
		<?
		$lastname=$ds->row["lastname"];
	}
	
	$ts=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
	
	$sig = md5 ( $site_payu_account . "" . $product_type . "-" . $product_id . $site_payu_password3 . ($product_total*100) . $product_name . "" . "" . $product_id . $_SESSION["people_name"] . $lastname . "" . "" . "" . "" . "" . "" . $_SESSION["people_email"] . "" . "" . $_SERVER["REMOTE_ADDR"] . $ts . $site_payu_password );
	?>
	<input type="hidden" name="sig" value="<?=$sig?>">
	<input type="hidden" name="ts" value="<?=$ts?>">
    <input type="hidden" name="pos_id" value="<?=$site_payu_account?>">
    <input type="hidden" name="pos_auth_key" value="<?=$site_payu_password3?>">
    <input type="hidden" name="session_id" value="<?=$product_type?>-<?=$product_id?>">
     <input type="hidden" name="order_id" value="<?=$product_id?>">
    <input type="hidden" name="amount" value="<?=($product_total*100)?>">
    <input type="hidden" name="desc" value="<?=$product_name?>">
    <input type="hidden" name="client_ip" value="<?=$_SERVER["REMOTE_ADDR"]?>">
    <input type="hidden" name="js" value="0">
	</form>
	<script language="JavaScript" type="text/javascript">
    	<!--
   		 document.forms['payform'].js.value=1;
    	-->
 	</script>
	<?

}
?>