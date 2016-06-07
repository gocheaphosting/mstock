<?
include("../admin/function/db.php");
include("payments_settings.php");


$pin=$site_payprin_password; 



if(!$_REQUEST['UMresponseHash']) 
{
	die('Gateway did not return a response hash'); 
}

$tmp = explode('/', $_REQUEST['UMresponseHash']);
$gatewaymethod = $tmp[0]; 
$gatewayseed = $tmp[1];
$gatewayhash = $tmp[2]; 



$prehash = $pin . ':' . $_REQUEST["UMresult"] . ':' . $_REQUEST["UMrefNum"] . ':' . $gatewayseed; 


if($gatewaymethod=='m') 
{
	$myhash=md5($prehash); 
}
elseif($gatewaymethod=='s')
{
	$myhash=sha1($prehash); 
}
else
{
	die('Unknown hash method'); 
}



if($myhash == $gatewayhash) 
{ 
	echo "Transaction response validated";
	if($_REQUEST["UMresult"]=="A")
	{
				$product_type=result($_REQUEST["UMdescription"]); 
				$transaction_id=result($_REQUEST["UMrefNum"]); 
				$id=(int)$_REQUEST["UMinvoice"];

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
else 
{ 
	echo "Invalid transaction response";
}



$db->close();

?>