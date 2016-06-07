<?if(!defined("site_root")){exit();}?>
<?
if($site_mollie_account!="")
{
	try
	{
		$api_key=$site_mollie_account;
		include "../admin/plugins/mollie/examples/initialize.php";

		$order_id = $product_type."-".$product_id;


		/*
		 * Payment parameters:
	 	*   amount        Amount in EUROs. This example creates a ˆ 10,- payment.
	 	*   description   Description of the payment.
	 	*   webhookUrl    Custom webhook location, used instead of the default webhook URL in the Website profile.
	 	*   redirectUrl   Redirect location. The customer will be redirected there after the payment.
	 	*   metadata      Custom metadata that is stored with the payment.
	 	*/
		$payment = $mollie->payments->create(array(
			"amount"       => float_opt($product_total,2),
			"description"  => $product_name,
			"webhookUrl"   => surl.site_root."/members/payments_mollie_go.php",
			"redirectUrl"  => surl.site_root."/members/payments_result.php?d=1",
			"metadata"     => array(
				"order_id" => $order_id,
			),
		));
		?>
		<form action="<?=$payment->getPaymentUrl()?>" method="POST" name="process" id="process"> 
		</form>
		<?
	}
	catch (Mollie_API_Exception $e)
	{
		echo "API call failed: " . htmlspecialchars($e->getMessage());
	}
}
?>