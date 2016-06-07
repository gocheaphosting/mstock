<?
include("../admin/function/db.php");
include("payments_settings.php");



if ($_GET) 
{
	$postdata = '*API=&API_XML_REQUEST='.urlencode('
	<?xml version="1.0" encoding="ISO-8859-1" ?>
	<wsb_api_request>
	<command>get_transaction</command>
	<authorization>
	<username>'.$site_webpay_login.'</username>
	<password>'.md5($site_webpay_password2).'</password>
	</authorization>
	<fields>
	<transaction_id>'.result($_GET["wsb_tid"]).'
	</transaction_id>
	</fields>
	</wsb_api_request>
	');

	if($site_webpay_test)
	{
		$curl = curl_init("https://sandbox.webpay.by");
	}
	else
	{
		$curl = curl_init("https://billing.webpay.by");
	}
	curl_setopt ($curl, CURLOPT_HEADER, 0);
	curl_setopt ($curl, CURLOPT_POST, 1);
	curl_setopt ($curl, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);
	$response = curl_exec ($curl);
	curl_close ($curl);

	$res= new RecursiveIteratorIterator(new SimpleXMLIterator($response));
 
	$arr = array();
	foreach ($res as $property => $value)
	{
   		$arr[$property][] = (string) $value;
	}


	$sec=md5(@$arr["transaction_id"][0].@$arr["batch_timestamp"][0].@$arr["currency_id"][0].@$arr["amount"][0].@$arr["payment_method"][0].@$arr["payment_type"][0].@$arr["order_id"][0].@$arr["rrn"][0].$site_webpay_password);



  			
	if($sec==@$arr["wsb_signature"][0])
	{    	
    	if(@$arr["payment_type"][0]==1 or @$arr["payment_type"][0]==4)
    	{
    		$mass=explode("-",result($_GET["wsb_order_num"]));
    		$product_type=$mass[0];
    		$id=(int)$mass[1];
    		$transaction_id=transaction_add("webpay",result($_GET["wsb_tid"]),result($product_type),$id);
    		
			if($product_type=="credits")
			{
				credits_approve($id,$transaction_id);
				send_notification('credits_to_user',$id);
				send_notification('credits_to_admin',$id);
			}

			if($product_type=="subscription")
			{
				subscription_approve($id);
				send_notification('subscription_to_user',$id);
				send_notification('subscription_to_admin',$id);
			}

			if($product_type=="order")
			{
				order_approve($id);
				commission_add($id);

				coupons_add(order_user($id));
				send_notification('neworder_to_user',$id);
				send_notification('neworder_to_admin',$id);
			}
    	}
	}
}

header("HTTP/1.0 200 OK");

include("../inc/header.php");
?>

<h1><?=word_lang("payment")?></h1>

<?
if(@$arr["payment_type"][0]==1 or @$arr["payment_type"][0]==4)
{
	?>
	<p>Thank you! Your transaction has been sent successfully.</p>
	<?
}
else
{
	?>
	<p>Error. The transaction has been declined!</p>
	<?
}
?>




<?include("../inc/footer.php");?>