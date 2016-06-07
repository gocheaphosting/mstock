<?
include("../admin/function/db.php");
include("payments_settings.php");

if($site_segpay_account!="")
{

	if($site_segpay_ipn==true)
	{
			if (strtolower($_REQUEST['approved']) == 'yes')
			{
				if($_REQUEST["product_type"]=="credits")
				{
					credits_approve($_REQUEST["product_id"],$_REQUEST["trans_id"]);
					send_notification('credits_to_user',$_REQUEST["product_id"]);
					send_notification('credits_to_admin',$_REQUEST["product_id"]);
				}

				if($_REQUEST["product_type"]=="subscription")
				{
					subscription_approve($_REQUEST["product_id"]);
					send_notification('subscription_to_user',$_REQUEST["product_id"]);
					send_notification('subscription_to_admin',$_REQUEST["product_id"]);
				}
				echo("success");
			}
	}
}

$db->close();
?>