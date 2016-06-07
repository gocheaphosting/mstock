<?
include("../admin/function/db.php");
include("payments_settings.php");

include("../admin/plugins/verotel/FlexPay.php");

$signature_check = FlexPay::validate_signature($site_verotel_password, $_REQUEST);


if($signature_check)
{
			$crc=explode("-",$_REQUEST["referenceID"]);
    		$id=(int)$crc[1];
    		$product_type=result($crc[0]);

				$transaction_id=transaction_add("verotel",result($_REQUEST["SaleID"]),$product_type,$id);

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

ob_clean();
ob_end_flush();
echo "OK"; 

$db->close();
?>