<?
if(!defined("site_root")){exit();}

include($_SERVER["DOCUMENT_ROOT"].site_root."/members/payments_settings.php");

if(isset($_GET["id"]))
{
	$sql="select invoice_number,order_id,order_type,comments,refund from invoices where invoice_number=".(int)$_GET["id"];
	$ds->open($sql);
	if(!$ds->eof)
	{
		$flag=false;
		$billing_info= array();
		$shipping_info= array();
		$order_info=array();
		
		$user_sql="";
		if(isset($_SESSION["people_id"]) and !isset($_SESSION["entry_admin"]))
		{
			if($ds->row["order_type"]=="orders")
			{
				$user_sql=" user=".(int)$_SESSION["people_id"]." and ";
			}
			if($ds->row["order_type"]=="credits")
			{
				$user_sql=" user='".result($_SESSION["people_login"])."' and ";
			}
			if($ds->row["order_type"]=="subscription")
			{
				$user_sql=" user='".result($_SESSION["people_login"])."' and ";
			}
		}
		
		
		if($ds->row["order_type"]=="orders")
		{
	
			
			$sql="select id,shipping_firstname,shipping_lastname,shipping_address,shipping_country,shipping_city,shipping_state,shipping_zip,billing_firstname,billing_lastname,billing_address,billing_country,billing_city,billing_zip,billing_state,user,total,data,credits,billing_company,billing_business,billing_vat from orders where ".$user_sql." id=".$ds->row["order_id"];
			$rs->open($sql);
			if(!$rs->eof)
			{
				$flag=true;
				
				$order_info["id"]=$rs->row["id"];
				$order_info["user"]=user_url_back($rs->row["user"]);
				$order_info["name"]=word_lang("order");
				$order_info["total"]=$rs->row["total"];
				$order_info["date"]=date(date_short,$rs->row["data"]);
				$order_info["credits"]=$rs->row["credits"];
				
				$billing_info["billing_firstname"]=$rs->row["billing_firstname"];
				$billing_info["billing_lastname"]=$rs->row["billing_lastname"];
				$billing_info["billing_address"]=$rs->row["billing_address"];
				$billing_info["billing_country"]=$rs->row["billing_country"];
				$billing_info["billing_city"]=$rs->row["billing_city"];
				$billing_info["billing_zip"]=$rs->row["billing_zip"];
				$billing_info["billing_state"]=$rs->row["billing_state"];
				$order_info["company"] = $rs->row["billing_company"];
				$order_info["business"] = $rs->row["billing_business"];
				$order_info["vat"] = $rs->row["billing_vat"];
				
				$shipping_info["shipping_firstname"]=$rs->row["shipping_firstname"];
				$shipping_info["shipping_lastname"]=$rs->row["shipping_lastname"];
				$shipping_info["shipping_address"]=$rs->row["shipping_address"];
				$shipping_info["shipping_country"]=$rs->row["shipping_country"];
				$shipping_info["shipping_city"]=$rs->row["shipping_city"];
				$shipping_info["shipping_zip"]=$rs->row["shipping_zip"];
				$shipping_info["shipping_state"]=$rs->row["shipping_state"];
			}
		}
		if($ds->row["order_type"]=="credits")
		{
			$sql="select id_parent,billing_firstname,billing_lastname,billing_address,billing_country,billing_city,billing_zip,billing_state,title,user,data,total,billing_company,billing_business,billing_vat  from credits_list where ".$user_sql." id_parent=".$ds->row["order_id"];
			$rs->open($sql);
			if(!$rs->eof)
			{
				$flag=true;
				
				$order_info["id"]=word_lang("credits")."-".$rs->row["id_parent"];
				$order_info["user"]=$rs->row["user"];
				$order_info["name"]=word_lang("credits").": ".$rs->row["title"];
				$order_info["total"]=$rs->row["total"];
				$order_info["date"]=date(date_short,$rs->row["data"]);
				$order_info["credits"]=0;
				
				$billing_info["billing_firstname"]=$rs->row["billing_firstname"];
				$billing_info["billing_lastname"]=$rs->row["billing_lastname"];
				$billing_info["billing_address"]=$rs->row["billing_address"];
				$billing_info["billing_country"]=$rs->row["billing_country"];
				$billing_info["billing_city"]=$rs->row["billing_city"];
				$billing_info["billing_zip"]=$rs->row["billing_zip"];
				$billing_info["billing_state"]=$rs->row["billing_state"];
				$order_info["company"] = $rs->row["billing_company"];
				$order_info["business"] = $rs->row["billing_business"];
				$order_info["vat"] = $rs->row["billing_vat"];
				
				$shipping_info["shipping_firstname"]="";
				$shipping_info["shipping_lastname"]="";
				$shipping_info["shipping_address"]="";
				$shipping_info["shipping_country"]="";
				$shipping_info["shipping_city"]="";
				$shipping_info["shipping_zip"]="";
				$shipping_info["shipping_state"]="";
			}
		}
		if($ds->row["order_type"]=="subscription")
		{
			$sql="select id_parent,billing_firstname,billing_lastname,billing_address,billing_country,billing_city,billing_zip,billing_state,title,user,data1,total,billing_company,billing_business,billing_vat from subscription_list where ".$user_sql." id_parent=".$ds->row["order_id"];
			$rs->open($sql);
			if(!$rs->eof)
			{
				$flag=true;
				
				$order_info["id"]=word_lang("subscription")."-".$rs->row["id_parent"];
				$order_info["user"]=$rs->row["user"];
				$order_info["name"]=word_lang("subscription").": ".$rs->row["title"];
				$order_info["total"]=$rs->row["total"];
				$order_info["date"]=date(date_short,$rs->row["data1"]);
				$order_info["credits"]=0;
				
				$billing_info["billing_firstname"]=$rs->row["billing_firstname"];
				$billing_info["billing_lastname"]=$rs->row["billing_lastname"];
				$billing_info["billing_address"]=$rs->row["billing_address"];
				$billing_info["billing_country"]=$rs->row["billing_country"];
				$billing_info["billing_city"]=$rs->row["billing_city"];
				$billing_info["billing_zip"]=$rs->row["billing_zip"];
				$billing_info["billing_state"]=$rs->row["billing_state"];
				$order_info["company"] = $rs->row["billing_company"];
				$order_info["business"] = $rs->row["billing_business"];
				$order_info["vat"] = $rs->row["billing_vat"];
				
				$shipping_info["shipping_firstname"]="";
				$shipping_info["shipping_lastname"]="";
				$shipping_info["shipping_address"]="";
				$shipping_info["shipping_country"]="";
				$shipping_info["shipping_city"]="";
				$shipping_info["shipping_zip"]="";
				$shipping_info["shipping_state"]="";
			}
		}
		

	
		if($flag)
		{
			?>
			
			
			
			
			
			
			<?
			$invoice_content=file_get_contents($_SERVER["DOCUMENT_ROOT"].site_root."/templates/invoice/invoice.tpl");
			
			if(file_exists($DOCUMENT_ROOT."/content/invoice_logo.jpg"))
			{
				$invoice_content = str_replace("{LOGO}",site_root."/content/invoice_logo.jpg",$invoice_content);
			}
			else
			{
				$invoice_content = str_replace("{LOGO}",site_root."/images/e.gif",$invoice_content);
			}
			
			$invoice_content = str_replace("{COMPANY_NAME}",$global_settings["company_name"],$invoice_content);
			$invoice_content = str_replace("{COMPANY_ADDRESS1}",$global_settings["company_address1"],$invoice_content);
			$invoice_content = str_replace("{COMPANY_ADDRESS2}",$global_settings["company_address2"],$invoice_content);
			
			if($global_settings["company_vat_number"] != "")
			{
				$invoice_content = str_replace("{COMPANY_VAT}","<b>EU VAT Reg No:</b> ".$global_settings["company_vat_number"],$invoice_content);
			}
			else
			{
				$invoice_content = str_replace("{COMPANY_VAT}","",$invoice_content);
			}
			
			if($ds->row["refund"])
			{
				$invoice_content = str_replace("{INVOICE}",word_lang("Credit notes"),$invoice_content);
				$invoice_content = str_replace("{PAID}",site_root."/admin/images/refund_stamp.jpg",$invoice_content);
				$invoice_content = str_replace("{INVOICE_NUMBER}",$global_settings["credit_notes_prefix"].(int)$_GET["id"],$invoice_content);
				$invoice_content = str_replace("{INVOICE_AMOUNT}","-".currency(1,false).float_opt($order_info["total"],2)." ".currency(2,false),$invoice_content);
				$invoice_content = str_replace("{ITEMS}",show_order_content($ds->row["order_type"],$ds->row["order_id"],"-"),$invoice_content);
			}
			else
			{
				$invoice_content = str_replace("{INVOICE}",word_lang("Invoice"),$invoice_content);
				$invoice_content = str_replace("{PAID}",site_root."/admin/images/paid_stamp.jpg",$invoice_content);	
				$invoice_content = str_replace("{INVOICE_NUMBER}",$global_settings["invoice_prefix"].(int)$_GET["id"],$invoice_content);
				$invoice_content = str_replace("{INVOICE_AMOUNT}",currency(1,false).float_opt($order_info["total"],2)." ".currency(2,false),$invoice_content);
				$invoice_content = str_replace("{ITEMS}",show_order_content($ds->row["order_type"],$ds->row["order_id"],""),$invoice_content);
			}
						
			$invoice_content = str_replace("{INVOICE_DATE}",$order_info["date"],$invoice_content);
			$invoice_content = str_replace("{ORDER_NUMBER}",$order_info["id"],$invoice_content);
			$invoice_content = str_replace("{TEXT}",$ds->row["comments"],$invoice_content);
			
			if(@$order_info["business"] == 1)
			{
				$invoice_content = str_replace("{CLIENT_NAME}",$order_info["company"],$invoice_content);
				$invoice_content = str_replace("{CLIENT_VAT}",word_lang("VAT number").": ".$order_info["vat"],$invoice_content);
			}
			else
			{
				$invoice_content = str_replace("{CLIENT_NAME}",$billing_info["billing_firstname"] . " " .$billing_info["billing_lastname"],$invoice_content);
				$invoice_content = str_replace("{CLIENT_VAT}","",$invoice_content);
			}
			
			$invoice_content = str_replace("{CLIENT_ADDRESS}",str_replace("\n","<br>",$billing_info["billing_address"]),$invoice_content);
			
			
			
			$transaction_info = '';
			$transaction_flag= false;
			
			$sql="select id_parent,user,data,total,ip,tnumber,ptype,pid,processor from payments where pid=".$ds->row["order_id"];
			$rs->open($sql);
			if(!$rs->eof)
			{
				$transaction_flag= true;
				
				if(isset($payments[$rs->row["processor"]]))
				{
					$transaction_info = word_lang("Payment received via") . ' ' . $payments[$rs->row["processor"]] ;
				}

				if($rs->row["tnumber"]!="")
				{
					$transaction_info .= "(".word_lang("transaction id").": ".$rs->row["tnumber"].")";
				}
			}
			
			$invoice_content = str_replace("{PAYMENT}",$transaction_info,$invoice_content);
			
			$invoice_content=format_layout($invoice_content,"payment",$transaction_flag);
			
			$invoice_content = str_replace("{SITE_ROOT}",surl.site_root,$invoice_content);
			
			$invoice_content = translate_text($invoice_content);
		}
	}
}
?>