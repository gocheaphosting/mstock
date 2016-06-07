<?php
define('GOID', $site_gopay_account);
define('SECURE_KEY', $site_gopay_password);

/*
 * defaultni jazykova mutace platebni brany GoPay
 */
define('LANG', 'cs');

/*
 * URL eshopu - pro urceni absolutnich cest 
 */
define('HTTP_SERVER', surl.site_root);

define('SUCCESS_URL', HTTP_SERVER . '/members/payments_result.php?d=1');
define('FAILED_URL', HTTP_SERVER . '/members/payments_result.php?d=2');

/*
 * URL skriptu volaneho pri navratu z platebni brany
 */
define('CALLBACK_URL', HTTP_SERVER . '/members/payments_gopay_go.php');

/*
 * URL skriptu vytvarejiciho platbu na GoPay
 */
define('ACTION_URL', HTTP_SERVER . '/members/payments_gopay.php');

/**
 *  Volba Testovaciho ci Provozniho prostredi
 *  Testovaci prostredi - GopayConfig::TEST
 *  Provozni prostredi  - GopayConfig::PROD
 */
require_once(dirname(__FILE__) . "/../api/gopay_config.php");

if($site_gopay_test)
{
	GopayConfig::init(GopayConfig::TEST);
}
else
{
	GopayConfig::init(GopayConfig::PROD);
}
?>