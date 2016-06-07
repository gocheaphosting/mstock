<?if(!defined("site_root")){exit();}?>
<?

$payments=array();
$payments["2checkout"]="2Checkout";
$payments["authorize"]="Authorize";
$payments["cashu"]="CashU";
//$payments["cashx"]="CashX";
$payments["ccavenue"]="CCAvenue";
$payments["ccbill"]="ccBill";
$payments["checkoutfi"]="Checkout.fi";
$payments["cheque"]="Cheque or Money order";
$payments["chronopay"]="ChronoPay";
$payments["clickbank"]="ClickBank";
$payments["dotpay"]="Dotpay";
$payments["dwolla"]="Dwolla";
//$payments["egold"]="Egold";
$payments["enets"]="eNETs";
//$payments["epassporte"]="ePassporte";
$payments["epay"]="Epay";
$payments["epaykkbkz"]="Epay.kkb.kz";
$payments["epoch"]="Epoch";
$payments["eway"]="eWay";
$payments["fortumo"]="Fortumo";
$payments["google"]="Google Checkout";
$payments["goemerchant"]="GoEMerchant";
$payments["gopay"]="GoPay";
$payments["inetcash"]="InetCash";
$payments["linkpoint"]="Linkpoint";
$payments["mellatbank"]="Mellat bank";
$payments["moneyua"]="Money.ua";
$payments["mollie"]="Mollie";
$payments["multicards"]="MultiCards";
$payments["myvirtualmerchant"]="MyVirtualMerchant";
$payments["nmi"]="Network Merchants";
$payments["nochex"]="Nochex";
$payments["pagseguro"]="PagSeguro";
$payments["payfast"]="PayFast";
//$payments["paylikemagic"]="PayLikeMagic";
$payments["paypal"]="Paypal";
$payments["paypalpro"]="Paypal PRO";
$payments["payprin"]="PayPrin";
$payments["paysera"]="Paysera";
$payments["payson"]="Payson";
$payments["alertpay"]="Payza";
$payments["paxum"]="Paxum";
$payments["payu"]="PayU";
$payments["payumoney"]="PayUMoney";
$payments["privatbank"]="PrivatBank.ua";
$payments["qiwi"]="QIWI";
$payments["robokassa"]="Robokassa";
$payments["rbkmoney"]="RBK Money";
$payments["secpay"]="SECPay";
$payments["segpay"]="Segpay";
$payments["moneybookers"]="Skrill (Moneybookers)";
$payments["stripe"]="Stripe";
$payments["targetpay"]="Targetpay";
$payments["transferuj"]="Transferuj";
$payments["verotel"]="Verotel";
$payments["victoriabank"]="Victoria bank";
$payments["webmoney"]="Webmoney";
$payments["webpay"]="WebPay.by";
$payments["worldpay"]="Worldpay";
$payments["yandex"]="Yandex.Money";
$payments["zombaio"]="Zombaio";










//Paypal gateway
$site_paypal_account="";
$site_paypal_ipn=0;
$sql="select account,ipn,url,activ from gateway_paypal";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_paypal_account=$rs->row["account"];
	$site_paypal_ipn=$rs->row["ipn"];
	if(!defined("paypal_url"))
	{
		define( "paypal_url", $rs->row["url"] );
	}
}


//Paypal PRO gateway
$site_paypalpro_account="";
$site_paypalpro_password="";
$site_paypalpro_signature="";
$site_paypalpro_ipn=0;

$sql="select account,ipn,password,signature,activ from gateway_paypalpro";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_paypalpro_account=$rs->row["account"];
	$site_paypalpro_password=$rs->row["password"];
	$site_paypalpro_signature=$rs->row["signature"];
	$site_paypalpro_ipn=$rs->row["ipn"];
}




//Authorize gateway
$site_authorize_account="";
$site_authorize_account2="";
$site_authorize_ipn=0;

$sql="select activ,account,txnkey,ipn,url from gateway_authorize";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_authorize_account=$rs->row["account"];
	$site_authorize_account2=$rs->row["txnkey"];
	$site_authorize_ipn=$rs->row["ipn"];
	if(!defined("authorize_url"))
	{
		define( "authorize_url", $rs->row["url"] );
	}
}


//2Checkout gateway
$site_2checkout_account="";
$site_2checkout_ipn=0;

$sql="select activ,account,ipn,url from gateway_2checkout";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_2checkout_account=$rs->row["account"];
	$site_2checkout_ipn=$rs->row["ipn"];
	if(!defined("checkout2_url"))
	{
		define( "checkout2_url", $rs->row["url"] );
	}
}



//E-gold gateway
$site_egold_account="";
$site_egold_name="";
$site_egold_ipn=0;

$sql="select activ,account,name,ipn,url from gateway_egold";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_egold_account=$rs->row["account"];
	$site_egold_name=$rs->row["name"];
	$site_egold_ipn=$rs->row["ipn"];
	if(!defined("egold_url"))
	{
		define( "egold_url", $rs->row["url"] );
	}
}


//Worldpay gateway
$site_worldpay_account="";
$site_worldpay_ipn=0;

$sql="select activ,account,ipn,url from gateway_worldpay";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_worldpay_account=$rs->row["account"];
	$site_worldpay_ipn=$rs->row["ipn"];
	if(!defined("worldpay_url"))
	{
		define( "worldpay_url", $rs->row["url"] );
	}
}

//Linkpoint gateway
$site_linkpoint_account="";
$site_linkpoint_ipn=0;

$sql="select activ,account,ipn,url from gateway_linkpoint";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_linkpoint_account=$rs->row["account"];
	$site_linkpoint_ipn=$rs->row["ipn"];
	if(!defined("linkpoint_url"))
	{
		define( "linkpoint_url", $rs->row["url"] );
	}
}


//E-passporte gateway
$site_epassporte_account="";
$site_epassporte_code="";
$site_epassporte_ipn=0;

$sql="select activ,account,pcode,ipn,url from gateway_epassporte";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_epassporte_account=$rs->row["account"];
	$site_epassporte_code=$rs->row["pcode"];
	$site_epassporte_ipn=$rs->row["ipn"];
	if(!defined("epassporte_url"))
	{
		define( "epassporte_url", $rs->row["url"] );
	}
}


//Chronopay gateway
$site_chronopay_account="";
$site_chronopay_code="";
$site_chronopay_ipn=0;

$sql="select activ,account,ekey,ipn,url from gateway_chronopay";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_chronopay_account=$rs->row["account"];
	$site_chronopay_code=$rs->row["ekey"];
	$site_chronopay_ipn=$rs->row["ipn"];
	if(!defined("chronopay_url"))
	{
		define( "chronopay_url", $rs->row["url"] );
	}
}


//Secpay gateway
$site_secpay_account="";
$site_secpay_password="";
$site_secpay_subject="";
$site_secpay_message="";
$site_secpay_ipn=0;

$sql="select activ,account,password,subject,message,ipn,url from gateway_secpay";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_secpay_account=$rs->row["account"];
	$site_secpay_password=$rs->row["password"];
	$site_secpay_subject=$rs->row["subject"];
	$site_secpay_message=$rs->row["message"];
	$site_secpay_ipn=$rs->row["ipn"];
	if(!defined("secpay_url"))
	{
		define( "secpay_url", $rs->row["url"] );
	}
}



//Moneybookers gateway
$site_moneybookers_account="";
$site_moneybookers_password="";
$site_moneybookers_ipn=0;

$sql="select activ,account,ipn,url,password from gateway_moneybookers";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_moneybookers_account=$rs->row["account"];
	$site_moneybookers_ipn=$rs->row["ipn"];
	$site_moneybookers_password=$rs->row["password"];
	if(!defined("moneybookers_url"))
	{
		define( "moneybookers_url", $rs->row["url"] );
	}
}



//Nochex gateway
$site_nochex_account="";
$site_nochex_ipn=0;

$sql="select activ,account,ipn,url from gateway_nochex";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_nochex_account=$rs->row["account"];
	$site_nochex_ipn=$rs->row["ipn"];
	if(!defined("nochex_url"))
	{
		define( "nochex_url", $rs->row["url"] );
	}
}


//eWay gateway
$site_eway_account="";
$site_eway_ipn=0;

$sql="select activ,account,ipn,url from gateway_eway";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_eway_account=$rs->row["account"];
	$site_eway_ipn=$rs->row["ipn"];
	if(!defined("eway_url"))
	{
		define( "eway_url", $rs->row["url"] );
	}
}


//eNETS gateway
$site_enets_account="";
$site_enets_ipn=0;

$sql="select activ,account,ipn,url from gateway_enets";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_enets_account=$rs->row["account"];
	$site_enets_ipn=$rs->row["ipn"];
	if(!defined("enets_url"))
	{
		define( "enets_url", $rs->row["url"] );
	}
}


//Segpay gateway
$site_segpay_account="";
$site_segpay_ipn=0;

$sql="select activ,ipn,url from gateway_segpay where subscription=0 and credits=0";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_segpay_account='yes';
	$site_segpay_ipn=$rs->row["ipn"];

}
if(!$rs->eof)
{
	if(!defined("segpay_url"))
	{
		define( "segpay_url", $rs->row["url"] );
	}
}




//Google Checkout gateway
$site_google_account="";
$site_google_ipn=0;

$sql="select activ,account,mkey,ipn,url from gateway_google";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_google_account=$rs->row["account"];
	$site_google_key=$rs->row["mkey"];
	$site_google_ipn=$rs->row["ipn"];
	if(!defined("google_url"))
	{
		define( "google_url", $rs->row["url"] );
	}
}


//CashU gateway
$site_cashu_account="";
$site_cashu_ipn=0;

$sql="select activ,account,ecode,ipn,url from gateway_cashu";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_cashu_account=$rs->row["account"];
	$site_cashu_key=$rs->row["ecode"];
	$site_cashu_ipn=$rs->row["ipn"];
	if(!defined("cashu_url"))
	{
		define( "cashu_url", $rs->row["url"] );
	}
}


//Webmoney gateway
$site_webmoney_account="";
$site_webmoney_ipn=0;

$sql="select activ,account,ecode,ipn,url from gateway_webmoney";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_webmoney_account=$rs->row["account"];
	$site_webmoney_key=$rs->row["ecode"];
	$site_webmoney_ipn=$rs->row["ipn"];
	if(!defined("webmoney_url"))
	{
		define( "webmoney_url", $rs->row["url"] );
	}
}


//Epoch gateway
$site_epoch_account="";
$site_epoch_ipn=0;

$sql="select activ,account,ipn,url from gateway_epoch where subscription=0 and credits=0";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_epoch_account=$rs->row["account"];
	$site_epoch_ipn=$rs->row["ipn"];
}
if(!$rs->eof)
{
	if(!defined("epoch_url"))
	{
		define( "epoch_url", $rs->row["url"] );
	}
}


//ccBill gateway
$site_ccbill_account="";
$site_ccbill_account2="";
$site_ccbill_account3="";
$site_ccbill_ipn=0;

$sql="select activ,account,account2,account3,ipn,url from gateway_ccbill where subscription=0 and credits=0";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_ccbill_account=$rs->row["account"];
	$site_ccbill_account2=$rs->row["account2"];
	$site_ccbill_account3=$rs->row["account3"];
	$site_ccbill_ipn=$rs->row["ipn"];
}
if(!$rs->eof)
{
	if(!defined("ccbill_url"))
	{
		define( "ccbill_url", $rs->row["url"] );
	}
}





//Multicards gateway
$site_multicards_account="";
$site_multicards_account2="";
$site_multicards_account3="";
$site_multicards_ipn=0;

$sql="select activ,account,account2,password,ipn,url from gateway_multicards";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_multicards_account=$rs->row["account"];
	$site_multicards_account2=$rs->row["account2"];
	$site_multicards_account3=$rs->row["password"];
	$site_multicards_ipn=$rs->row["ipn"];
	if(!defined("multicards_url"))
	{
		define( "multicards_url", $rs->row["url"] );
	}
}


//ClickBank gateway
$site_clickbank_account="";
$site_clickbank_account2="";
$site_clickbank_account3="";
$site_clickbank_ipn=0;

$sql="select activ,account,account2,ipn,url from gateway_clickbank where subscription=0 and credits=0";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_clickbank_account=$rs->row["account"];
	$site_clickbank_account2=$rs->row["account2"];
	$site_clickbank_ipn=$rs->row["ipn"];
}
if(!$rs->eof)
{
	if(!defined("clickbank_url"))
	{
		define( "clickbank_url", $rs->row["url"] );
	}
}


//cashX
$site_cashx_account="";
$site_cashx_ipn=0;
$site_cashx_security="";

$sql="select activ,account,ipn,url,security_code from gateway_cashx";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_cashx_account=$rs->row["account"];
	$site_cashx_ipn=$rs->row["ipn"];
	$site_cashx_security=$rs->row["security_code"];
	if(!defined("cashx_url"))
	{
		define( "cashx_url", $rs->row["url"] );
	}
}


//Alertpay
$site_alertpay_account="";
$site_alertpay_ipn=0;
$site_alertpay_security="";

$sql="select activ,account,ipn,url,security_code from gateway_alertpay";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_alertpay_account=$rs->row["account"];
	$site_alertpay_ipn=$rs->row["ipn"];
	$site_alertpay_security=$rs->row["security_code"];
	if(!defined("alertpay_url"))
	{
		define( "alertpay_url", $rs->row["url"] );
	}
}


//Epay.bg
$site_epay_account="";
$site_epay_ipn=0;
$site_epay_security="";

$sql="select activ,account,ipn,url,security_code from gateway_epay";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_epay_account=$rs->row["account"];
	$site_epay_ipn=$rs->row["ipn"];
	$site_epay_security=$rs->row["security_code"];
	if(!defined("epay_url"))
	{
		define( "epay_url", $rs->row["url"] );
	}
}

//QIWI gateway
$site_qiwi_account="";
$site_qiwi_password="";
$site_qiwi_ipn=0;

$sql="select activ,account,password,ipn,url from gateway_qiwi";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_qiwi_account=$rs->row["account"];
	$site_qiwi_code=$rs->row["password"];
	$site_qiwi_ipn=$rs->row["ipn"];
}

//MyVirtualMerchant gateway
$site_myvirtualmerchant_account="";
$site_myvirtualmerchant_account2="";
$site_myvirtualmerchant_password="";
$site_myvirtualmerchant_ipn=0;

$sql="select activ,account,account2,password,ipn,url from gateway_myvirtualmerchant";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_myvirtualmerchant_account=$rs->row["account"];
	$site_myvirtualmerchant_account2=$rs->row["account2"];
	$site_myvirtualmerchant_code=$rs->row["password"];
	$site_myvirtualmerchant_ipn=$rs->row["ipn"];
}


//Fortumo gateway
$site_fortumo_account="";
$site_fortumo_password="";

$sql="select activ,account,password from gateway_fortumo";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_fortumo_account=$rs->row["account"];
	$site_fortumo_password=$rs->row["password"];
}


//Zombaio gateway
$site_zombaio_account="";
$site_zombaio_password="";
$site_zombaio_priceid="";

$sql="select activ,account,password,price from gateway_zombaio";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_zombaio_account=$rs->row["account"];
	$site_zombaio_password=$rs->row["password"];
	$site_zombaio_priceid=$rs->row["price"];
}


//Pagseguro gateway
$site_pagseguro_account="";
$site_pagseguro_password="";

$sql="select activ,account,password from gateway_pagseguro";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_pagseguro_account=$rs->row["account"];
	$site_pagseguro_password=$rs->row["password"];
}


//Robokassa gateway
$site_robokassa_account="";
$site_robokassa_password="";
$site_robokassa_password2="";

$sql="select activ,account,password,password2 from gateway_robokassa";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_robokassa_account=$rs->row["account"];
	$site_robokassa_password=$rs->row["password"];
	$site_robokassa_password2=$rs->row["password2"];
}


//RBK Money gateway
$site_rbkmoney_account="";
$site_rbkmoney_password="";

$sql="select activ,account,password from gateway_rbkmoney";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_rbkmoney_account=$rs->row["account"];
	$site_rbkmoney_password=$rs->row["password"];
}


//Epay.kkb.kz gateway
$site_epaykkbkz_account="";

$sql="select activ,account from gateway_epaykkbkz";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_epaykkbkz_account=$rs->row["account"];
}


//PayPrin gateway
$site_payprin_account="";
$site_payprin_password="";

$sql="select activ,account,password from gateway_payprin";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_payprin_account=$rs->row["account"];
	$site_payprin_password=$rs->row["password"];
}


//Dwolla gateway
$site_dwolla_account="";
$site_dwolla_apikey="";
$site_dwolla_apisecret="";
$site_dwolla_pin="";
$site_dwolla_test=0;

$sql="select activ,account,apikey,apisecret,test,pin from gateway_dwolla";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_dwolla_account=$rs->row["account"];
	$site_dwolla_apikey=$rs->row["apikey"];
	$site_dwolla_apisecret=$rs->row["apisecret"];
	$site_dwolla_test=$rs->row["test"];
	$site_dwolla_pin=$rs->row["pin"];
}


//Stripe gateway
$site_stripe_account="";
$site_stripe_password="";

$sql="select activ,account,password from gateway_stripe";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_stripe_account=$rs->row["account"];
	$site_stripe_password=$rs->row["password"];
}


//Money.ua gateway
$site_moneyua_account="";
$site_moneyua_password="";
$site_moneyua_test=1;
$site_moneyua_commission=0;

$sql="select activ,account,password,testmode,commission from gateway_moneyua";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_moneyua_account=$rs->row["account"];
	$site_moneyua_password=$rs->row["password"];
	$site_moneyua_test=$rs->row["testmode"];
	$site_moneyua_commission=$rs->row["commission"];
}


//Privatbank gateway
$site_privatbank_account="";
$site_privatbank_password="";

$sql="select activ,account,password from gateway_privatbank";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_privatbank_account=$rs->row["account"];
	$site_privatbank_password=$rs->row["password"];
}


//Paysera gateway
$site_paysera_account="";
$site_paysera_password="";

$sql="select activ,account,password from gateway_paysera";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_paysera_account=$rs->row["account"];
	$site_paysera_password=$rs->row["password"];
}


//Dotpay gateway
$site_dotpay_account="";
$site_dotpay_password="";

$sql="select activ,account,password from gateway_dotpay";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_dotpay_account=$rs->row["account"];
	$site_dotpay_password=$rs->row["password"];
}

//PayU gateway
$site_payu_account="";
$site_payu_password="";
$site_payu_password2="";
$site_payu_password3="";

$sql="select activ,account,password,password2,password3 from gateway_payu";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_payu_account=$rs->row["account"];
	$site_payu_password=$rs->row["password"];
	$site_payu_password2=$rs->row["password2"];
	$site_payu_password3=$rs->row["password3"];
}

//Paxum gateway
$site_paxum_account="";
$site_paxum_password="";

$sql="select activ,account,password from gateway_paxum";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_paxum_account=$rs->row["account"];
	$site_paxum_password=$rs->row["password"];
}


//NMI gateway
$site_nmi_account="";

$sql="select activ,account from gateway_nmi";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_nmi_account=$rs->row["account"];
}



//Payfast gateway
$site_payfast_account="";
$site_payfast_password="";

$sql="select activ,account,password from gateway_payfast";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_payfast_account=$rs->row["account"];
	$site_payfast_password=$rs->row["password"];
}


//InetCash gateway
$site_inetcash_account="";

$sql="select activ,account from gateway_inetcash";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_inetcash_account=$rs->row["account"];
}


//Paylikemagic gateway
$site_paylikemagic_account="";
$site_paylikemagic_password="";

$sql="select activ,account,password from gateway_paylikemagic";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_paylikemagic_account=$rs->row["account"];
	$site_paylikemagic_password=$rs->row["password"];
}


//GoPay gateway
$site_gopay_account="";
$site_gopay_password="";
$site_gopay_test=1;

$sql="select activ,account,password,test from gateway_gopay";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_gopay_account=$rs->row["account"];
	$site_gopay_password=$rs->row["password"];
	$site_gopay_test=$rs->row["test"];
}

//CCAvenue gateway
$site_ccavenue_account="";
$site_ccavenue_password="";
$site_ccavenue_password2="";

$sql="select activ,account,password,password2 from gateway_ccavenue";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_ccavenue_account=$rs->row["account"];
	$site_ccavenue_password=$rs->row["password"];
	$site_ccavenue_password2=$rs->row["password2"];
}

//GoEMerchant gateway
$site_goemerchant_account="";
$site_goemerchant_account2="";
$site_goemerchant_password="";

$sql="select activ,account,account2,password from gateway_goemerchant";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_goemerchant_account=$rs->row["account"];
	$site_goemerchant_account2=$rs->row["account2"];
	$site_goemerchant_password=$rs->row["password"];
}

//Mollie
$site_mollie_account="";

$sql="select activ,account from gateway_mollie";
$rs->open($sql);
if(!$rs->eof )
{
	if($rs->row["activ"]==1)
	{
		$site_mollie_account=$rs->row["account"];
	}
}



//Payson
$site_payson_account="";
$site_payson_account2="";
$site_payson_password="";

$sql="select activ,account,account2,password from gateway_payson";
$rs->open($sql);
if(!$rs->eof )
{
	if($rs->row["activ"]==1)
	{
		$site_payson_account=$rs->row["account"];
		$site_payson_account2=$rs->row["account2"];
		$site_payson_password=$rs->row["password"];
	}
}


//Victoriabank
$site_victoriabank_account="";
$site_victoriabank_account2="";

$sql="select activ,account,account2 from gateway_victoriabank";
$rs->open($sql);
if(!$rs->eof )
{
	if($rs->row["activ"]==1)
	{
		$site_victoriabank_account=$rs->row["account"];
		$site_victoriabank_account2=$rs->row["account2"];
	}
}




//Webpay gateway
$site_webpay_account="";
$site_webpay_password="";
$site_webpay_test=1;
$site_webpay_login="";
$site_webpay_password2="";

$sql="select * from gateway_webpay";
$rs->open($sql);
if(!$rs->eof and $rs->row["activ"]==1)
{
	$site_webpay_account=$rs->row["account"];
	$site_webpay_password=$rs->row["password"];
	$site_webpay_test=$rs->row["test"];
	$site_webpay_login=$rs->row["login"];
	$site_webpay_password2=$rs->row["password2"];
}



//Mellat bank
$site_mellatbank_account="";
$site_mellatbank_account2="";
$site_mellatbank_password="";

$sql="select activ,account,account2,password from gateway_mellatbank";
$rs->open($sql);
if(!$rs->eof )
{
	if($rs->row["activ"]==1)
	{
		$site_mellatbank_account=$rs->row["account"];
		$site_mellatbank_account2=$rs->row["account2"];
		$site_mellatbank_password=$rs->row["password"];
	}
}





//Cheque or money order
$site_cheque_account="";
$site_cheque_account2="";

$sql="select activ,account,account2 from gateway_cheque";
$rs->open($sql);
if(!$rs->eof )
{
	if($rs->row["activ"]==1)
	{
		$site_cheque_account=$rs->row["account"];
		$payments["cheque"]=$rs->row["account"];
		$site_cheque_account2=$rs->row["account2"];
	}
}



//Targetpay gateway
if($global_settings["targetpay_active"])
{
	$site_targetpay_account=$global_settings["targetpay_account"];
}
else
{
	$site_targetpay_account="";
}
$site_targetpay_test=$global_settings["targetpay_test"];


//Yandex.Money
if($global_settings["yandex_active"])
{
	$site_yandex_account=$global_settings["yandex_account"];
}
else
{
	$site_yandex_account="";
}
$site_yandex_account2=$global_settings["yandex_account2"];
$site_yandex_password=$global_settings["yandex_password"];
$site_yandex_test=$global_settings["yandex_test"];
$site_yandex_payments["PC"]="Оплата из кошелька в Яндекс.Деньгах";
$site_yandex_payments["AC"]="Оплата с произвольной банковской карты";
$site_yandex_payments["MC"]="Платеж со счета мобильного телефона";
$site_yandex_payments["GP"]="Оплата наличными через кассы и терминалы";
$site_yandex_payments["WM"]="Оплата из кошелька в системе WebMoney";
$site_yandex_payments["SB"]="Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн";
$site_yandex_payments["MP"]="Оплата через мобильный терминал (mPOS)";
$site_yandex_payments["AB"]="Оплата через Альфа-Клик";
$site_yandex_payments["МА"]="Оплата через MasterPass";
$site_yandex_payments["PB"]="Оплата через Промсвязьбанк";



//Transferuj gateway
if($global_settings["transferuj_active"])
{
	$site_transferuj_account=$global_settings["transferuj_account"];
}
else
{
	$site_transferuj_account="";
}
$site_transferuj_password=$global_settings["transferuj_password"];



//PayUMoney
if($global_settings["payumoney_active"])
{
	$site_payumoney_account=$global_settings["payumoney_account"];
}
else
{
	$site_payumoney_account="";
}
$site_payumoney_password=$global_settings["payumoney_password"];
$site_payumoney_test=$global_settings["payumoney_test"];



//Checkout.fi
if($global_settings["checkoutfi_active"])
{
	$site_checkoutfi_account=$global_settings["checkoutfi_account"];
}
else
{
	$site_checkoutfi_account="";
}
$site_checkoutfi_password=$global_settings["checkoutfi_password"];


//Verotel
if($global_settings["verotel_active"])
{
	$site_verotel_account=$global_settings["verotel_account"];
}
else
{
	$site_verotel_account="";
}
$site_verotel_password=$global_settings["verotel_password"];

?>