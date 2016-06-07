<?php
#############################################
# FlexPay PHP library version 3.1
#############################################
final class FlexPay {

    const FLEXPAY_URL       = 'https://secure.verotel.com/startorder';
    const STATUS_URL        = 'https://secure.verotel.com/salestatus';
    const PROTOCOL_VERSION  = '3';

    /**
     * FUNCTION get_signature:
     * -------------------------------
     * Returns SHA1 encoded signature
     * - IN $secret - STRING
     * - IN $params - ASOC_ARRAY
     * - RETURNS STRING - generated SHA1 signature
     */
    public static function get_signature($secret, $params) {
        $filtered = self::_filter_params($params);
        return self::_signature($secret, $filtered);
    }


    /**
     * FUNCTION validate_signature:
     * ------------------------------
     * Validates signature
     * - IN $secret - STRING
     * - IN $params - ASOC_ARRAY (just allowed params with signature)
     * - RETURNS BOLEAN - true/false
     */
    public static function validate_signature($secret, $params) {
        $sign1 = strtolower($params['signature']);
        unset($params['signature']);
        $sign2 = self::_signature($secret, $params);
        return ($sign1 === $sign2) ? true : false;
    }


    /**
     * FUNCTION get_purchase_URL:
     * -----------------------------
     * - IN $secret - STRING
     * - IN $params - ASOC_ARRAY
     * - RETURNS STRING - purchase_URL
     */
    public static function get_purchase_URL($secret, $params) {
        return self::_generate_URL(self::FLEXPAY_URL, $secret, 'purchase', $params);
    }


    /**
     * FUNCTION get_subscription_URL:
     * -----------------------------
     * - IN $secret - STRING
     * - IN $params - ASOC_ARRAY
     * - RETURNS STRING - subscription_URL
     */
    public static function get_subscription_URL($secret, $params) {
        return self::_generate_URL(self::FLEXPAY_URL, $secret, 'subscription', $params);
    }


    /**
     * FUNCTION get_status_URL:
     * ----------------------------
     * - IN $secret - STRING
     * - IN $params - ASOC_ARRAY
     * - RETURNS STRING - status_URL
     */
    public static function get_status_URL($secret, $params) {
        return self::_generate_URL(self::STATUS_URL, $secret, NULL, $params);
    }


    ######## PRIVATE ############################

    /**
     * Common furnction for generating signature
     * - IN $secret - STRING
     * - IN $params - ASOC_ARRAY
     */
    private static function _signature($secret, $params) {
        $outArray = array($secret);
        ksort($params, SORT_REGULAR);
        foreach ($params as $key => $value) {
            array_push($outArray, "$key=$value");
        }

        return strtolower(sha1(join(":", $outArray)));
    }


    /**
     * Returns url
     * - IN $baseURL - STRING (url : http://www.xyz.com)
     * - IN $secret  - STRING
     * - IN $params  - ASOC_ARRAY (URL params)
     */
    private static function _generate_URL($baseURL, $secret, $type, $params) {
        if (!isset($secret) || !is_string($secret) || empty($secret)) {
            throw new Exception("no secret given");
        }

        if (!isset($params) || empty($params)) {
            throw new Exception("no params given");
        }

        if (!is_array($params)) {
            throw new Exception("invalid params");
        }

        if (!empty($type)){
            $params['type'] = $type;
        }

        $params['version'] = self::PROTOCOL_VERSION;

        ksort($params, SORT_REGULAR);
        $outArray = array();
        foreach ($params as $key => $value) {
            if($value !== "") {
                $outArray[$key] = $value;
            }
        }

        $signature = self::get_signature($secret, $outArray);
        $outArray['signature'] = $signature;

        return self::_build_URL($baseURL, $outArray);
    }


    /**
     * Returns URL string
     * - IN $baseURL - STRING (url : http://www.xyz.com)
     * - IN $params  - ASOC_ARRAY - (URL params)
     */
    private static function _build_URL($baseURL, $params) {
        $arr = array();

        foreach ($params as $key => $value) {
            $arr[] = "$key=" . urlencode($value);
        }
        return $baseURL . "?" . join("&", $arr);
    }


    /**
     * Returns filtered parameters assoc-array
     * - IN $params - ASOC_ARRAY (unfiltered URL params)
     */
    private static function _filter_params($params) {

        $keys = array_keys($params);
        $filtered = array();
        $regexp = '/^(
            version
            | shopID
            | price(Amount|Currency)
            | paymentMethod
            | description
            | referenceID
            | saleID
            | custom[123]
            | subscriptionType
            | period
            | name
            | trialAmount
            | trialPeriod
            | cancelDiscountPercentage
            | type
            )$/x';

        foreach ($keys as $key) {
            if (preg_match($regexp, $key)) {
                $filtered[$key] = $params[$key];
            }
        }

        return $filtered;
    }
}
