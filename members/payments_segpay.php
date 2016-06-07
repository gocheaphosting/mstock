<?if(!defined("site_root")){exit();}?>
<?
if($site_segpay_account!="")
{
		$apackage=0;
		$aproduct=0;

		if($_POST["tip"]=="credits")
		{
			$sql="select * from gateway_segpay where credits=".(int)$_POST["credits"];
			$ds->open($sql);
			if(!$ds->eof)
			{
				$apackage=$ds->row["package_id"];
				$aproduct=$ds->row["product_id"];
			}
		}

		if($_POST["tip"]=="subscription")
		{
			$sql="select * from gateway_segpay where subscription=".(int)$_POST["subscription"];
			$ds->open($sql);
			if(!$ds->eof)
			{
				$apackage=$ds->row["package_id"];
				$aproduct=$ds->row["product_id"];
			}
		}
		?>
		<form  name="process" id="process" action="<?=segpay_url?>" method="post">
		<input type="hidden" name="x-eticketid" value="<?=$apackage?>:<?=$aproduct?>">
		<input type="hidden" name="x-auth-link" value="<?=surl.site_root."/members/payments_result.php?d=1"?>">
		<input type="hidden" name="x-auth-text" value="Click here to return to the store">
		<input type="hidden" name="product_id" value="<?=$product_id?>"/>
		<input type="hidden" name="product_type" value="<?=$product_type?>"/>
		</form>
		<?
}
?>