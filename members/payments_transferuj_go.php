<?
include("../admin/function/db.php");
include("payments_settings.php");


$hash=md5($site_transferuj_account . $_REQUEST["tr_id"] . $_REQUEST["tr_amount"] . $_REQUEST["tr_crc"] . $site_transferuj_password );


if($_REQUEST["tr_status"] and $hash==$_REQUEST["md5sum"] and $_SERVER["REMOTE_ADDR"]=="195.149.229.109" and $_POST['tr_error'] =="none")
{
			$crc=explode("-",$_REQUEST["tr_crc"]);
    		$id=(int)$crc[1];
    		$product_type=result($crc[0]);

				$transaction_id=transaction_add("transferuj",result($_REQUEST["tr_id"]),$product_type,$id);

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

echo "TRUE"; 

$db->close();
?>