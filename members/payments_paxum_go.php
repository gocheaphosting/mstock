<?
include("../admin/function/db.php");
include("payments_settings.php");


// Getting Raw POST Data
$rawPostedData = file_get_contents('php://input');

// Extracting Field=Value Pairs
$i = strpos($rawPostedData, "&key=");
$fieldValuePairsData = substr($rawPostedData, 0, $i);

// Calculating Key (Notification Signature)
$calculatedKey = md5($fieldValuePairsData . $site_paxum_password);

// Verifying Notification Key (Signature)
$isValid = $_POST["key"] == $calculatedKey ? true : false;

if(!$isValid)
{
	echo "This is not a valid notification message";
	exit;
}

    		$mass=explode("-",result($_POST["transaction_item_id"]));
    		$product_type=$mass[0];
    		$id=(int)$mass[1];
    		$transaction_id=transaction_add("paxum",(int)$_POST["transaction_id"],result($product_type),$id);

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
$db->close();
?>