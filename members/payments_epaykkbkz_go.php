<?
include("../admin/function/db.php");
include("payments_settings.php");


require_once("../admin/plugins/epaykkbkz/kkb.utils.php");


$self = $_SERVER['PHP_SELF'];
$path1 = '../admin/plugins/epaykkbkz/config.txt';
$result = 0;
if(isset($_POST["response"])){$response = $_POST["response"];};
$result = process_response(stripslashes($response),$path1);
//foreach ($result as $key => $value) {echo $key." = ".$value."<br>";};
if (is_array($result)){
	if (in_array("ERROR",$result)){
		if ($result["ERROR_TYPE"]=="ERROR"){
			echo "System error:".$result["ERROR"];
		} elseif ($result["ERROR_TYPE"]=="system"){
			echo "Bank system error > Code: '".$result["ERROR_CODE"]."' Text: '".$result["ERROR_CHARDATA"]."' Time: '".$result["ERROR_TIME"]."' Order_ID: '".$result["RESPONSE_ORDER_ID"]."'";
		}elseif ($result["ERROR_TYPE"]=="auth"){
			echo "Bank system user autentication error > Code: '".$result["ERROR_CODE"]."' Text: '".$result["ERROR_CHARDATA"]."' Time: '".$result["ERROR_TIME"]."' Order_ID: '".$result["RESPONSE_ORDER_ID"]."'";
		};
	};
	if (in_array("DOCUMENT",$result)){
		echo "Result DATA: <BR>";
		foreach ($result as $key => $value) {echo "Postlink Result: ".$key." = ".$value."<br>";};
		
		//Test
		//$result["ORDER_ORDER_ID"]="000123";
		//$result["CUSTOMER_MAIL"]="buyer@cmsaccount.com";
		
		//Oпределяем что это заказ, кредитс или подписка
		$id=(int)$result["ORDER_ORDER_ID"];
		$email=$result["CUSTOMER_MAIL"];

		
		//User
		$sql="select id_parent,login from users where email='".result($email)."'";
		$rs->open($sql);
		if(!$rs->eof)
		{
			//Orders
			$sql="select id from orders where id=".$id." and user=".$rs->row["id_parent"]." and status=0";
			$ds->open($sql);
			if(!$ds->eof)
			{
				order_approve($id);
				commission_add($id);

				coupons_add(order_user($id));
				send_notification('neworder_to_user',$id);
				send_notification('neworder_to_admin',$id);
			}
			
			//Credits
			$sql="select id_parent from credits_list where id_parent=".$id." and user='".$rs->row["login"]."' and approved=0";
			$ds->open($sql);
			if(!$ds->eof)
			{
				credits_approve($id,$result["PAYMENT_REFERENCE"]);
				send_notification('credits_to_user',$id);
				send_notification('credits_to_admin',$id);
			}
			
			//Subscription
			$sql="select id_parent from subscription_list where id_parent=".$id." and user='".$rs->row["login"]."' and approved=0";
			$ds->open($sql);
			if(!$ds->eof)
			{
				subscription_approve($id);
				send_notification('subscription_to_user',$id);
				send_notification('subscription_to_admin',$id);
			}
		}

		
		
		
		
		
		
	};
} else { echo "System error".$result; };






?>