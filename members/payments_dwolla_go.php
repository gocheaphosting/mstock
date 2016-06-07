<?$site="payments_thanks";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>

<h1><?=word_lang("payment")?></h1>

<?
if(isset($_GET["error"]))
{
	echo("<p><b>".result($_GET["error"])."</b></p>");
	echo("<p>".result($_GET["error_description"])."</b>");
}
elseif(isset($_GET["signature"]))
{
	
	function verifyGatewaySignature($proposedSignature, $checkoutId, $amount) 
	{
    	global $site_dwolla_apisecret;
    	$amount = number_format($amount, 2);
    	$signature = hash_hmac("sha1", "{$checkoutId}&{$amount}", $site_dwolla_apisecret);
   	 	return $signature == $proposedSignature;
	}
	
	$didVerify = verifyGatewaySignature($_GET["signature"], $_GET["checkoutId"], $_GET["amount"]);
	
	if($didVerify and $_GET["postback"]=="success")
	{
				
				$prod=explode("-",result($_GET["orderId"]));
				$id=(int)$prod[1];
				$desc=$prod[0];
				
				$transaction_id=transaction_add("dwolla",result($_GET["transaction"]),$desc,$id);

				if($desc=="credits")
				{
					credits_approve($id,$transaction_id);
					send_notification('credits_to_user',$id);
					send_notification('credits_to_admin',$id);
				}

				if($desc=="subscription")
				{
					subscription_approve($id);
					send_notification('subscription_to_user',$id);
					send_notification('subscription_to_admin',$id);
				}

				if($desc=="order")
				{
					order_approve($id);
					commission_add($id);

					coupons_add(order_user($id));
					send_notification('neworder_to_user',$id);
					send_notification('neworder_to_admin',$id);
				}
				
		echo("Thank you! Your transaction has been sent successfully.");
	}
	else
	{
		echo("Error. The transaction has been declined!");
	}
}
else
{
	echo("<p><b>Error!</b> No data.</p>");
}
?>


<?include("../inc/footer.php");?>