<?
include("../admin/function/db.php");
include("payments_settings.php");

// регистрационная информация (пароль #2)
// registration info (password #2)
$mrh_pass2 = $site_robokassa_password2;



// чтение параметров
// read parameters
$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$shp_item = $_REQUEST["Shp_item"];
$crc = $_REQUEST["SignatureValue"];


$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item"));

// build own CRC 
//$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2")); 
if (strtoupper($my_crc) != strtoupper($crc)) 
{ 
	echo "bad sign\n"; exit(); 
}

// признак успешно проведенной операции
// success
echo "OK$inv_id\n";


    		$id=(int)$inv_id;
    		$product_type=result($shp_item);

				$transaction_id=transaction_add("robokassa",'',$product_type,$id);

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