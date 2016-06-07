<?if(!defined("site_root")){exit();}?>
<?
if($site_google_account!=""){

$merchant_id = $site_google_account;  // Your Merchant ID
      $merchant_key = $site_google_key;  // Your Merchant Key
      $server_type = "checkout";
      $currency = $currency_code1;


if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
if($site_google_ipn==true)
{



  require_once('../admin/plugins/google_checkout/googleresponse.php');
  require_once('../admin/plugins/google_checkout/googlemerchantcalculations.php');
  require_once('../admin/plugins/google_checkout/googleresult.php');
  require_once('../admin/plugins/google_checkout/googlerequest.php');



  $Gresponse = new GoogleResponse($merchant_id, $merchant_key);

  $Grequest = new GoogleRequest($merchant_id, $merchant_key, $server_type, $currency);

  // Retrieve the XML sent in the HTTP POST request to the ResponseHandler
  $xml_response = isset($HTTP_RAW_POST_DATA)?
                    $HTTP_RAW_POST_DATA:file_get_contents("php://input");
  if (get_magic_quotes_gpc()) {
    $xml_response = stripslashes($xml_response);
  }
  list($root, $data) = $Gresponse->GetParsedXML($xml_response);





$kauth = base64_encode($merchant_id .":". $merchant_key);


header("Authorization: Basic $kauth");
header("Accept: application/xml");


  switch ($root) {
    case "new-order-notification": {


	if(eregi("credits",$data[$root]['shopping-cart']['items']['item']['item-name']['VALUE']))
	{
$tp="credits";
	}

	if(eregi("subscription",$data[$root]['shopping-cart']['items']['item']['item-name']['VALUE']))
	{
$tp="subscription";
	}

	if(eregi("order",$data[$root]['shopping-cart']['items']['item']['item-name']['VALUE']))
	{
$tp="order";
	}

	$transaction_id=transaction_add("google checkout",$data[$root]['google-order-number']['VALUE'],$tp,$data[$root]['shopping-cart']['items']['item']['item-description']['VALUE']);


break;
}
    case "order-state-change-notification": {


      $new_financial_state = $data[$root]['new-financial-order-state']['VALUE'];

      if($new_financial_state=="CHARGED") 
{



$sql="select ptype,pid from payments where tnumber='".$data[$root]['google-order-number']['VALUE']."'";
$ds->open($sql);
if(!$ds->eof)
{

	if($ds->row["ptype"]=="credits")
	{
		credits_approve($ds->row["pid"],0);
									send_notification('credits_to_user',$ds->row["pid"]);
send_notification('credits_to_admin',$ds->row["pid"]);
	}

	if($ds->row["ptype"]=="subscription")
	{
		subscription_approve($ds->row["pid"]);
						send_notification('subscription_to_user',$ds->row["pid"]);
send_notification('subscription_to_admin',$ds->row["pid"]);
	}

	if($ds->row["ptype"]=="order")
	{
		order_approve($ds->row["pid"]);
		commission_add($ds->row["pid"]);
		coupons_add(order_user($ds->row["pid"]));
										send_notification('neworder_to_user',$ds->row["pid"]);
send_notification('neworder_to_admin',$ds->row["pid"]);
	}

}



}


      break;
    }
    default:
      break;
  }


  function get_arr_result($child_node) {
    $result = array();
    if(isset($child_node)) {
      if(is_associative_array($child_node)) {
        $result[] = $child_node;
      }
      else {
        foreach($child_node as $curr_node){
          $result[] = $curr_node;
        }
      }
    }
    return $result;
  }


  function is_associative_array( $var ) {
    return is_array( $var ) && !is_numeric( implode( '', array_keys( $var ) ) );
  }




















}
}
else
{
  require_once('../admin/plugins/google_checkout/googlecart.php');
  require_once('../admin/plugins/google_checkout/googleitem.php');
  require_once('../admin/plugins/google_checkout/googleshipping.php');
  require_once('../admin/plugins/google_checkout/googletax.php');





      $cart = new GoogleCart($merchant_id, $merchant_key, $server_type,$currency);
      $total_count = $product_total;
      
      $item_1 = new GoogleItem($product_type.": ".$product_name,      // Item name
                               $product_id, // Item      description
                               1, // Quantity
                               $product_total); // Unit price
      $cart->AddItem($item_1);
      

 
    
      // Specify "Return to xyz" link
      $cart->SetContinueShoppingUrl(surl.site_root);
      
      // Request buyer's phone number
      $cart->SetRequestBuyerPhone(true);
      
      // Display Google Checkout button
      echo $cart->CheckoutButtonCode("SMALL");



}



}
?>