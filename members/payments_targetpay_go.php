<?
include("../admin/function/db.php");
include("payments_settings.php");

if($site_targetpay_account!="")
{




if ( isset($_POST['rtlo'])&&isset($_POST['trxid'])&& isset($_POST['status'])) 
{
	if(($status = CheckReturnurl($site_targetpay_account, $_POST['trxid'] ))=="000000 OK" )
	{
		$flag=true;
	}
	else
	{
		$flag=false;
	}
	
	if(HandleReporturl($_POST['rtlo'], $_POST['trxid'], $_POST['status'] ) and $flag)
	{
	
			$mass=explode("-",result($_POST["description"]));
    		$product_type=$mass[0];
    		$id=(int)$mass[1];
    		$transaction_id=transaction_add("targetpay",result($_POST['trxid']),result($product_type),$id);
    		
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





function CheckReturnurl($rtlo, $trxid)
{
	$once=1;
	$test=0; // Set to 1 for testing as described in paragraph 1.3
	$url= "https://www.targetpay.com/ideal/check?".
	"rtlo=".$rtlo.
	"&trxid=".$trxid.
	"&once=".$once.
	"&test=".$test;
	return httpGetRequest($url);
}


function HandleReporturl($rtlo, $trxid, $status )
{
	if( substr($_SERVER['REMOTE_ADDR'],0,10) == "89.184.168" ||
	substr($_SERVER['REMOTE_ADDR'],0,9) == "78.152.58" ){


		return true;
	}
	else
	{
		return false;
	}
}

function httpGetRequest($url)
{
	$ch = curl_init( $url );
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
	$strResponse = curl_exec($ch);
	curl_close($ch);
	if ( $strResponse === false )
		die("Could not fetch response " . $url );
		return $strResponse;
}



}
?>