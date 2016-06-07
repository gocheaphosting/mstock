<?
$site="paypalpro";
include("../admin/function/db.php");
include("payments_settings.php");

if(!isset($_POST["product_id"]) or !isset($_POST["product_name"]) or !isset($_POST["product_total"]) or !isset($_POST["product_type"]))
{
	exit();
}

?>
<?include("../inc/header.php");?>

<h1><?=word_lang("payment")?> - PGoEMerchant</h1>

<?




$product_id=(int)$_POST["product_id"];
$product_name=result($_POST["product_name"]);
$product_total=$_POST["product_total"];
$product_type=result($_POST["product_type"]);





$buyer_info=array();
get_buyer_info($_SESSION["people_id"],$product_id,$product_type);

$order_info=array();
get_order_info($product_id,$product_type);

//Check if Total is correct
if(!check_order_total($product_total,$product_type,$product_id))
{
	exit();
}



$data = array();
$data['transaction_center_id'] = $site_goemerchant_account;
$data['gateway_id'] = $site_goemerchant_account2;
$data['operation_type'] = "SALE";
$data['recurring'] = "0";
$data['order_id'] = $product_type."-".$product_id;
$data['remote_ip_address'] = $_SERVER['REMOTE_ADDR'];
$data['card_name'] = result($_POST["card_type"]);
$data['owner_firstname'] =  $buyer_info["name"];
$data['owner_lastname'] =  $buyer_info["lastname"];
$data['owner_address'] = $buyer_info["billing_address"];
$data['owner_city'] = $buyer_info["billing_city"];
$data['owner_state'] = $buyer_info["billing_state"];
$data['owner_zip'] = $buyer_info["billing_zipcode"];
$data['owner_email'] = $buyer_info["email"];
$data['owner_phone'] = $buyer_info["telephone"];
$data['total'] = float_opt($product_total,2);
$data['card_number'] = result($_POST["card_number"]);
$data['card_exp'] = result($_POST["card_month"]).substr(result($_POST["card_year"]),2,2);
$data['cvv2'] = result($_POST["cvv"]);



$xml = new DOMDocument('1.0', 'utf-8');
 
$transaction = $xml->createElement("TRANSACTION");
 
$xml->appendChild($transaction);    
 
$fields = $xml->createElement("FIELDS");
 
$transaction->appendChild($fields);
 
foreach ($data as $key => $value){
 
	$attr = $xml->createAttribute('KEY');
 
	$field = $xml->createElement("FIELD",$value);
 
	$attr->value = $key;
 
	$field->appendChild($attr);
 
	$fields->appendChild($field);
 
}
 
$xml_output = $xml->saveXML();

//echo($xml_output);

$ch = curl_init("https://secure.goemerchant.com/secure/gateway/xmlgateway.aspx");
 
curl_setopt($ch, CURLOPT_POST, 1);
 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
 
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_output);
 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 
$result = curl_exec($ch);
 
curl_close($ch);


$xml = df_removeNewlines($result);
 
$status = df_fetchBetween('<FIELD KEY="status">','</FIELD>',$xml);
 
if ($status != '1'){
 
	$error1 = df_fetchBetween('<FIELD KEY="error">','</FIELD>',$xml);
 
	$error2 = df_fetchBetween('<FIELD KEY="auth_response">','</FIELD>',$xml);
	
	echo("<p><b>Error. The transaction has been declined!</b></p><p>".$error1 ."</p>");
	echo("<p>".$error2 ."</p>");
 
}
 
else {
 
	$auth_code = df_fetchBetween('<FIELD KEY="auth_code">','</FIELD>',$xml);
 	$order_id = df_fetchBetween('<FIELD KEY="order_id">','</FIELD>',$xml);
	$reference_number = df_fetchBetween('<FIELD KEY="reference_number">','</FIELD>',$xml);
	
	$mass=explode("-",$order_id);
    		$product_type=$mass[0];
    		$id=(int)$mass[1];
    		$transaction_id=transaction_add("goemerchant",$reference_number,result($product_type),$id);

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



function df_fetchBetween($needle1,$needle2,$haystack,$include=false){
 
	$position = strpos($haystack,$needle1);
 
	if ($position === false) { return null; }
 
	if ($include == false) $position += strlen($needle1);
 
	$position2 = strpos($haystack,$needle2,$position);
 
	if ($position2 === false) { return null; }
 
	if ($include == true) $position2 += strlen($needle2);
 
	$length = $position2 - $position;
 
	$substring = substr($haystack, $position, $length);
 
	return trim($substring);
 
}
 
 
function df_removeNewlines($input){
 
	return str_replace(array("\t","\n","\r","\x20\x20","\0","\x0B"), "", html_entity_decode($input));
 
}



	if(isset($_POST["product_id"]) and isset($_POST["product_type"]))
	{
		$_GET["product_id"]=$_POST["product_id"];
		$_GET["product_type"]=$_POST["product_type"];
		$_GET["print"]=1;
		include("payments_statement.php");
	}
?>





<?include("../inc/footer.php");?>