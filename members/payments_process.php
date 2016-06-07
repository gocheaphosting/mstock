<?
include("../admin/function/db.php");

include("payments_settings.php");



$sent=false;
$product_id=0;
$product_name="Test";
$product_type="order";
$product_total="0.00";



//Payment forms
if(isset($_POST["tip"]) or isset($_GET["tip"]))
{
	if(isset($_POST["tip"]) and $_POST["tip"]=="credits")
	{
		$sql="select * from credits where id_parent=".(int)$_POST["credits"];
		$rs->open($sql);
		if(!$rs->eof)
		{
			$taxes_info=array();
			$product_id=credits_add($rs->row["id_parent"]);
			$product_name=$rs->row["title"];
			$sql="select total from credits_list where id_parent=".$product_id;
			$ds->open($sql);
			if(!$ds->eof)
			{
				$product_total=$ds->row["total"];
			}
			$product_type="credits";
			$sent=true;

		}
	}

	if(isset($_GET["tip"]) and $_GET["tip"]=="order" and isset($_GET["order_id"]))
	{
		$sql="select * from orders where id=".(int)$_GET["order_id"];
		$rs->open($sql);
		if(!$rs->eof)
		{
			$product_id=$rs->row["id"];
			$product_name="Order #".$rs->row["id"];
			$product_total=$rs->row["total"];
			$product_type="order";
			$sent=true;
		}
	}
	
	if(isset($_POST["tip"]) and $_POST["tip"]=="subscription")
	{
		$sql="select * from subscription where id_parent=".(int)$_POST["subscription"];
		$rs->open($sql);
		if(!$rs->eof)
		{
			$taxes_info=array();
			$product_id=subscription_add($rs->row["id_parent"]);	
			$product_name=$rs->row["title"]." - ".$_SESSION["people_login"];
			$sql="select total from subscription_list where id_parent=".$product_id;
			$ds->open($sql);
			if(!$ds->eof)
			{
				$product_total=$ds->row["total"];
			}
			$product_type="subscription";
			$recurring=$rs->row["recurring"];
			$recurring_days=$rs->row["days"];
			$sent=true;
		}
	}

	if(isset($_GET["payment"]))
	{
		$_POST["payment"]=$_GET["payment"];
	}
	
	if($product_total==0)
	{
		if($product_type=="credits")
		{
			credits_approve($product_id,"");
			send_notification('credits_to_user',$product_id);
			send_notification('credits_to_admin',$product_id);
		}

		if($product_type=="subscription")
		{
			subscription_approve($product_id);
			send_notification('subscription_to_user',$product_id);
			send_notification('subscription_to_admin',$product_id);
		}

		if($product_type=="order")
		{
			order_approve($product_id);
			commission_add($product_id);

			coupons_add(order_user($product_id));
			send_notification('neworder_to_user',$product_id);
			send_notification('neworder_to_admin',$product_id);
		}
		
		header("location:".surl.site_root."/members/payments_result.php?d=1");
		exit();
	}


	if($_POST["payment"]!="paypalpro" and $_POST["payment"]!="myvirtualmerchant" and $_POST["payment"]!="stripe" and $_POST["payment"]!="nmi" and $_POST["payment"]!="goemerchant" and $_POST["payment"]!="checkoutfi")
	{
		if($_POST["payment"]=="inetcash")
		{
			header("location:https://www.inet-cash.com/mc/shop/start/".$site_inetcash_account."?shopid=".$product_type."-".$product_id."&lang=en&zahlart=cc");
			exit();
		}
		
		if($_POST["payment"]!="cheque")
		{
			echo("<html><head><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"/></head>");
			echo("<body "); 

			if($_POST["payment"]!="google" and $_POST["payment"]!="epoch" and $_POST["payment"]!="clickbank" and $_POST["payment"]!="gopay")
			{
				echo("onLoad=\"document.process.submit()\" bgcolor='#525151'");
			}

			echo(">");


			if($_POST["payment"]!="clickbank" and $_POST["payment"]!="google" and $_POST["payment"]!="epoch" and $_POST["payment"]!="gopay")
			{
				echo("<div style='margin:250px auto 0px auto;width:100px;background-color:#373737;border: #4a4a4a 4px solid;padding:20px;font: 15pt Arial;color:#ffffff'>".word_lang("loading")."...<div><center><img src='".site_root."/images/upload_loading.gif'></center></div></div>");
			}


			if($sent==true)
			{
				include("payments_".preg_replace('/[^a-z0-9]/i',"",$_POST["payment"]).".php");
			}

			echo("</body></html>");
		}
		else
		{
			transaction_add("cheque",'',$product_type,$product_id);
			
			if($product_type=="credits")
			{
				send_notification('credits_to_admin',$product_id);
			}

			if($product_type=="subscription")
			{
				send_notification('subscription_to_admin',$product_id);
			}

			if($product_type=="order")
			{
				send_notification('neworder_to_admin',$product_id);
			}
			header("location:payments_cheque.php?product_id=".$product_id."&product_type=".$product_type."&print=1");
			exit();
		}
	}
	else
	{
		/*
		$fields["payment"]=preg_replace('/[^a-z0-9]/i',"",$_POST["payment"]);
		$fields["product_id"]=$product_id;
		$fields["product_name"]=$product_name;
		$fields["product_total"]=$product_total;
		$fields["product_type"]=$product_type;
		$fields["people_id"]=$_SESSION["people_id"];
		$fields["people_name"]=$_SESSION["people_name"];
		$fields["people_login"]=$_SESSION["people_login"];
		$fields["people_email"]=$_SESSION["people_email"];
		$fields["people_category"]=$_SESSION["people_category"];
		$fields["people_active"]=$_SESSION["people_active"];
		$fields["people_type"]=$_SESSION["people_type"];
		$fields["people_exam"]=$_SESSION["people_exam"];

		
		$fields_string = '';
		foreach($fields as $key=>$value) { $fields_string .=$key.'='.$value.'&'; }
		rtrim($fields_string, "&");

		$url=ssurl.site_root."/members/payments_cc.php";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$result = curl_exec($ch);
		curl_close($ch);
		*/
		
		$payment_url="payments_cc.php";
		if($_POST["payment"]=="stripe")
		{
			$payment_url="payments_stripe.php";
		}
		if($_POST["payment"]=="nmi")
		{
			$payment_url="payments_nmi.php";
		}
		if($_POST["payment"]=="checkoutfi")
		{
			$payment_url="payments_checkoutfi.php";
		}
		
		
		echo("<html>");
		echo("<body onLoad=\"document.process.submit()\" bgcolor='#525151'><div style='margin:250px auto 0px auto;width:100px;background-color:#373737;border: #4a4a4a 4px solid;padding:20px;font: 15pt Arial;color:#ffffff'>".word_lang("loading")."...<div><center><img src='".site_root."/images/upload_loading.gif'></center></div></div><form method='post' name='process' id='process' action='".ssurl.site_root."/members/".$payment_url."'>");
		echo("<input type='hidden' name='payment' value='".preg_replace('/[^a-z0-9]/i',"",$_POST["payment"])."'>");
		echo("<input type='hidden' name='product_id' value='".$product_id."'>");
		echo("<input type='hidden' name='product_name' value='".$product_name."'>");
		echo("<input type='hidden' name='product_total' value='".$product_total."'>");
		echo("<input type='hidden' name='product_type' value='".$product_type."'>");
		echo("</form></body>");
		echo("</html>");
		
		
		//header("location:".ssurl.site_root."/members/payments_cc.php?payment=".urlencode(preg_replace('/[^a-z0-9]/i',"",$_POST["payment"]))."&product_id=".$product_id."&product_name=".urlencode($product_name)."&product_total=".$product_total."&product_type=".$product_type);
		//exit();
	}

}







//Payment notification
if(isset($_GET["mode"]) and $_GET["mode"]=="notification" and isset($_GET["processor"]))
{
	include("payments_".preg_replace('/[^a-z0-9]/i',"",$_GET["processor"]).".php");
}

$db->close();
?>