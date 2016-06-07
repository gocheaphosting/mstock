<?
include("../admin/function/db.php");
include("payments_settings.php");
ob_start();
ob_clean();



$hash = md5($_POST['action'].';'.$_POST['orderSumAmount'].';'.$_POST['orderSumCurrencyPaycash'].';'.
					$_POST['orderSumBankPaycash'].';'.$_POST['shopId'].';'.$_POST['invoiceId'].';'.
					$_POST['customerNumber'].';'.$site_yandex_password);
header('Content-Type: application/xml');

if (strtolower($hash) != strtolower($_POST['md5']) and (isset($_POST['md5']))) {
			$code = 1;
			echo '<?xml version="1.0" encoding="UTF-8"?><checkOrderResponse performedDatetime="'. $_POST['requestDatetime'] .'" code="'.$code.'"'. ' invoiceId="'. $_POST['invoiceId'] .'" shopId="'. $site_yandex_account .'" message="bad md5"/>';
			exit();
}

$crc=explode("-",$_POST['orderNumber']);
$id=(int)$crc[1];
$product_type=result($crc[0]);

$product_total=0;

if ($product_type == 'credits') {
	$sql='select total from credits_list where id_parent='.(int)$id;
	$rs->open($sql);
	if (!$rs->eof) {
		$product_total = $rs->row["total"];
	}
}
if ($product_type == 'subscription') {
	$sql='select total from subscription_list where id_parent='.(int)$id;
	$rs->open($sql);
	if (!$rs->eof) {
		$product_total = $rs->row["total"];
	}		
}
if ($product_type == 'order') {
	$sql='select total from orders where id='.(int)$id;
	$rs->open($sql);
	if (!$rs->eof) {
		$product_total = $rs->row["total"];
	}		
}

if ($_POST['action'] == 'checkOrder') {
	if ($product_total != $_POST['orderSumAmount']) {
		$code = 100;
	} else {
		$code = 0;
	}	
	$answer = '<?xml version="1.0" encoding="UTF-8"?><checkOrderResponse performedDatetime="'.date('c').'" code="' . $code . '" invoiceId="'.$_POST['invoiceId'].'" shopId="' . $site_yandex_account . '" />';
	echo($answer);
	exit();
}

if ($_POST['action'] == 'paymentAviso') {
	if ($product_total == $_POST['orderSumAmount']) {
		$transaction_id=transaction_add("yandex.money","",$product_type,$id);
	
		if ($product_type == "credits") {
			credits_approve($id,$transaction_id);
			send_notification('credits_to_user',$id);
			send_notification('credits_to_admin',$id);
		}
	
		if ($product_type == "subscription") {
			subscription_approve($id);
			send_notification('subscription_to_user',$id);
			send_notification('subscription_to_admin',$id);
		}
	
		if ($product_type == "order") {
			order_approve($id);
			commission_add($id);
			coupons_add(order_user($id));
			send_notification('neworder_to_user',$id);
			send_notification('neworder_to_admin',$id);
		}
	}

	$answer = '<?xml version="1.0" encoding="UTF-8"?><paymentAvisoResponse performedDatetime="'.date('c').'" code="0" invoiceId="'.$_POST['invoiceId'].'" shopId="' . $site_yandex_account . '" />';
	echo($answer);
	exit();
}

$db->close();
?>