<?php
if(!defined("site_root")){exit();}
/**
 * Vytvoreni platby na strane GoPay a nasledne presmerovani na platebni branu
 */

require_once('../admin/plugins/gopay/example/config.php');
require_once('../admin/plugins/gopay/example/order.php');

require_once('../admin/plugins/gopay/api/gopay_helper.php');
require_once('../admin/plugins/gopay/api/gopay_soap.php');

/*
 * Pole kodu platebnich metod, ktere se zobrazi na brane. 
 * Hodnoty viz vzorovy e-shop, zalozka "Prehled aktivnich platebnich metod"
 * Zde nastaveno zobrazovani platebnich metod GoPay penezenka, platebni karty VISA, MasterCard, ePlatby a SuperCASH
 * Pro zobrazovani vsech platebnich metod ponechte pole prazdne
 */
//$paymentChannels = array("eu_pr_sms", "eu_bank");
$paymentChannels = array('cz_cs_c','eu_gp_u','eu_cg','SUPERCASH','eu_pr_sms','cz_mp','cz_kb','cz_rb','cz_mb','cz_fb','sk_uni','sk_sp','sk_vubbank','sk_tatrabank','sk_pabank','sk_sberbank','sk_otpbank','sk_csob','eu_bank','eu_gp_w');

/*
 * Platebni metoda, ktere je prvotne vybrana na brane. 
 * Zde nastaveno prvotni vybrani platebni metody platebni karty VISA, MasterCard
 */
$defaultPaymentChannel = ""; 

$p1 = null;
$p2 = null;
$p3 = null;
$p4 = null;

/*
 * Nacist data objednavky, zde z testovacich duvodu vse napevno primo v testovaci tride Order
 * Upravte dle ulozeni vasich objednavek 
 */
$order = new Order();
$order->load(); // ! UPRAVTE !
$order->orderNumber=$product_type."-".$product_id;
$order->productName=$product_name;
$order->totalPrice=$product_total*100;
$order->currency=$currency_code1;

$buyer_info=array();
get_buyer_info($_SESSION["people_id"],$product_id,$product_type);

$order->firstName=$buyer_info["name"];
$order->lastName=$buyer_info["lastname"];
$order->city=$buyer_info["billing_city"];
$order->street=$buyer_info["billing_address"];
$order->postalCode=$buyer_info["billing_zipcode"];
$order->countryCode=$mcountry_code[$buyer_info["billing_country"]];
$order->email=$buyer_info["email"];
$order->phoneNumber=$buyer_info["telephone"];


/*
 * Vytvoreni platby na strane GoPay prostrednictvim API funkce
 * Pokud vytvoreni probehne korektne, je navracen identifikator $paymentSessionId
 * Pokud nastane chyba, je vyhozena vyjimka
 */

try {
	$paymentSessionId = GopaySoap::createPayment((float)GOID,
												$order->getProductName(),
										 		(int)$order->getTotalPrice(),
												$order->getCurrency(),
												$order->getOrderNumber(),
												CALLBACK_URL,
												CALLBACK_URL,
												$paymentChannels,
												$defaultPaymentChannel,
												SECURE_KEY,
												$order->firstName,
												$order->lastName,
												$order->city,
												$order->street,
												$order->postalCode,
												$order->countryCode,
												$order->email,
												$order->phoneNumber,
												$p1,
												$p2,
												$p3,
												$p4,
												LANG);

} catch (Exception $e) {
	/*
	 *  Osetreni chyby v pripade chybneho zalozeni platby
	 */

$link=FAILED_URL . "&sessionState=" . GopayHelper::FAILED;
	?>
	<script language='javascript'>
		function ff()
		{
		location.href='<?=$link?>';
		}
		
		ff();
		</script>
	<?
	exit;
}

/*
 * Platba na strane GoPay uspesne vytvorena
 * Ulozeni paymentSessionId k objednavce. Slouzi pro komunikaci s GoPay
 */
$order->setPaymentSessionId($paymentSessionId);
$order->save(); // ! UPRAVTE !

$encryptedSignature = GopayHelper::encrypt(
				GopayHelper::hash(
					GopayHelper::concatPaymentSession((float)GOID,
													(float)$paymentSessionId, 
													SECURE_KEY)
					), SECURE_KEY);			

/*
 * Presmerovani na platebni branu GoPay s predvybranou platebni metodou GoPay penezenka ($defaultPaymentChannel)
 */
$link = GopayConfig::fullIntegrationURL() . "?sessionInfo.targetGoId=" . GOID . "&sessionInfo.paymentSessionId=" . $paymentSessionId . "&sessionInfo.encryptedSignature=" . $encryptedSignature;
//exit;
?>
		<script language='javascript'>
		function ff()
		{
		location.href='<?=$link?>';
		}
		
		ff();
		</script>
