<?
if(!defined("site_root")){exit();}

if($site_paypal_account!="")
{
	if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
	{
		if($site_paypal_ipn==true)
		{
			$postdata=""; 
 			foreach ($_POST as $key=>$value) $postdata.=$key."=".urlencode($value)."&"; 
 			$postdata .= "cmd=_notify-validate"; 

 			$curl = curl_init(paypal_url); 
 			/*
  			curl_setopt ($curl, CURLOPT_HEADER, 0); 
  			curl_setopt ($curl, CURLOPT_POST, 1); 
  			curl_setopt ($curl, CURLOPT_POSTFIELDS, $postdata); 
 			curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0); 
  			curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1); 
  			curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0); 
  			*/
  			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close')); 
  			$response = curl_exec ($curl); 
  			curl_close ($curl); 

  			/*
  			$response = "VERIFIED";
  			$_POST["txn_type"] = "subscr_payment";
  			$_POST["payment_status"] = "Completed";
  			$_POST["item_number"]=35;
  			*/
			
			//Recurring subscriptions
			if ($response == "VERIFIED")
 			{
 				if($_POST["txn_type"] == "subscr_signup")
 				{
 					$sql="update subscription_list set subscr_id='".result($_POST["subscr_id"])."' where id_parent=".(int)$_POST["item_number"];
 					$db->execute($sql);
 					exit();
 				}
 			
 				if ($_POST["txn_type"] == "subscr_payment" and $_POST["payment_status"] == "Completed")  
 				{
 					$sql="select * from subscription_list where id_parent=".(int)$_POST["item_number"];
 					$ds->open($sql);
 					if(!$ds->eof)
 					{
 						if($ds->row["payments"]==0)
 						{
 						 	subscription_approve($_POST["item_number"]);
 						 	send_notification('subscription_to_user',$_POST["item_number"]);
							send_notification('subscription_to_admin',$_POST["item_number"]);
 						}
 						else
 						{
 							$sql="select days from subscription where id_parent=".$ds->row["subscription"];
 							$rs->open($sql);
 							if(!$rs->eof)
 							{
 								if(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))-$ds->row["recurring_data"]>23*3600)
 								{
 									$sql="update subscription_list set bandwidth=0,data2=data2+".(3600*24*$rs->row["days"]).",payments=payments+1,recurring_data=".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." where id_parent=".(int)$_POST["item_number"];
 									$db->execute($sql);
 								}
 							}
 						}
 					}
 					
 					exit();
 				}
 			}
			
			
			//Items
			if ($response == "VERIFIED" and $_POST["payment_status"] == "Completed" and ($_POST["txn_type"]=="web_accept" or $_POST["txn_type"]=="cart" or $_POST["txn_type"]=="send_money"))
			{
				$transaction_id=transaction_add("paypal",$_POST["txn_id"],$_GET["product_type"],$_POST["item_number"]);

				if($_GET["product_type"]=="credits")
				{
					credits_approve($_POST["item_number"],$transaction_id);
					send_notification('credits_to_user',$_POST["item_number"]);
					send_notification('credits_to_admin',$_POST["item_number"]);
				}

				if($_GET["product_type"]=="subscription")
				{
						subscription_approve($_POST["item_number"]);
						send_notification('subscription_to_user',$_POST["item_number"]);
						send_notification('subscription_to_admin',$_POST["item_number"]);
				}

				if($_GET["product_type"]=="order")
				{
					order_approve($_POST["item_number"]);
					commission_add($_POST["item_number"]);

					coupons_add(order_user($_POST["item_number"]));
					send_notification('neworder_to_user',$_POST["item_number"]);
					send_notification('neworder_to_admin',$_POST["item_number"]);
				}
				if($_GET["product_type"]=="payout_seller" or $_GET["product_type"]=="payout_affiliate")
				{
					payout_approve($_POST["item_number"],$_GET["product_type"]);
				}
			}
		}
	}
	else
	{
		?>
		<form method="post" name="process" id="process" action="<?=paypal_url?>">
		<input type="hidden" name="rm" value="2"/>
		<?if(isset($recurring) and $recurring==1){?>
			<input type="hidden" name="cmd" value="_xclick-subscriptions">
			<input type="hidden" name="a3" value="<?=$product_total?>">
			<input type="hidden" name="p3" value="<?=$recurring_days?>">
			<input type="hidden" name="t3" value="D">
			<input type="hidden" name="src" value="1">
			<input type="hidden" name="sra" value="1">
		<?}else{?>
			<input type="hidden" name="cmd" value="_xclick"/>
			<input type="hidden" name="amount" value="<?=$product_total?>"/>
		<?}?>
		<input type="hidden" name="business" value="<?=$site_paypal_account?>"/>
		<input type="hidden" name="cancel_return" value="<?=surl.site_root."/members/payments_result.php?d=2"?>"/>
		<input type="hidden" name="notify_url" value="<?=surl.site_root."/members/payments_process.php?mode=notification&product_type=".$product_type?>&processor=paypal"/>
		<input type="hidden" name="return" value="<?=surl.site_root."/members/payments_result.php?d=1"?>"/>
		<input type="hidden" name="item_name" value="<?=$product_name?>"/>
		<input type="hidden" name="item_number" value="<?=$product_id?>"/>
		<input type="hidden" name="currency_code" value="<?=$currency_code1?>"/>
		</form>
		<?
	}		
}
?>