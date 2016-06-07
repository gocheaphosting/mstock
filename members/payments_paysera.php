<?if(!defined("site_root")){exit();}?>
<?
require_once('../admin/plugins/paysera/WebToPay.php');



try {
    $request = WebToPay::redirectToPayment(array(
        'projectid'     => $site_paysera_account,
        'sign_password' => $site_paysera_password,
        'orderid'       => $product_type."-".$product_id,
        'amount'        => $product_total*100,
        'currency'      => $currency_code1,
        'country'       => 'LT',
        'accepturl'     => surl.site_root."/members/payments_result.php?d=1",
        'cancelurl'     => surl.site_root."/members/payments_result.php?d=2",
        'callbackurl'   => surl.site_root."/members/payments_paysera_go.php",
        'test'          => 0,
    ));
} catch (WebToPayException $e) {
    // handle exception
}
?>