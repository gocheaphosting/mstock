<?if(!defined("site_root")){exit();}?>
<?
if($site_targetpay_account!="")
{


function StartTransaction( $rtlo, $bank, $description, $amount, $returnurl, $reporturl)
{
	$url= "https://www.targetpay.com/ideal/start?".
	"rtlo=".$rtlo.
	"&bank=".$bank.
	"&description=".urlencode($description).
	"&amount=".$amount.
	"&returnurl=".urlencode($returnurl).
	"&reporturl=".urlencode($reporturl);
	$strResponse = httpGetRequest($url);
	$aResponse = explode('|', $strResponse );

	if ( !isset ( $aResponse[1] ) ) die('Error' . $aResponse[0] );
	$responsetype = explode ( ' ', $aResponse[0] );
	$trxid = $responsetype[1];

	if( $responsetype[0] == "000000" ) return $aResponse[1];
	else die($aResponse[0]);
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


$rtlo=$site_targetpay_account;
$bank=result($_REQUEST["targetpay_banks"]);
$description=$product_type."-".$product_id;
$amount=$product_total*100;
$returnurl=surl.site_root."/members/payments_result.php?d=1";
$reporturl=surl.site_root."/members/payments_targetpay_go.php";

$url = StartTransaction($rtlo,$bank, $description,$amount, $returnurl, $reporturl);

ob_start();
ob_clean();
header( "Location: ". $url );






}
?>