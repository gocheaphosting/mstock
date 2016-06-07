<?if(!defined("site_root")){exit();}?>
<?
if($site_epay_account!=""){


$secret     = $site_epay_security;

function hmac($algo,$data,$passwd){
        /* md5 and sha1 only */
        $algo=strtolower($algo);
        $p=array('md5'=>'H32','sha1'=>'H40');
        if(strlen($passwd)>64) $passwd=pack($p[$algo],$algo($passwd));
        if(strlen($passwd)<64) $passwd=str_pad($passwd,64,chr(0));

        $ipad=substr($passwd,0,64) ^ str_repeat(chr(0x36),64);
        $opad=substr($passwd,0,64) ^ str_repeat(chr(0x5C),64);

        return($algo($opad.pack($p[$algo],$algo($ipad.$data))));
}




if(isset($_GET["mode"]) and $_GET["mode"]=="notification")
{
	if($site_epay_ipn==true)
	{

		$ENCODED  = $_POST['encoded'];
		$CHECKSUM = $_POST['checksum'];

		# XXX Secret word with which merchant make CHECKSUM on the ENCODED packet
		$hmac   = hmac('sha1', $ENCODED, $secret); # XXX SHA-1 algorithm REQUIRED


		//$response = "VERIFIED";
		if ($hmac == $CHECKSUM)
		{

   			$data = base64_decode($ENCODED);
    		$lines_arr = split("\n", $data);
    		$info_data = '';
    
    		foreach ($lines_arr as $line) 
    		{
    			if (preg_match("/^INVOICE=(\d+):STATUS=(PAID|DENIED|EXPIRED)(:PAY_TIME=(\d+):STAN=(\d+):BCODE=([0-9a-zA-Z]+))?$/", $line, $regs)) 
    			{
					$invoice  = $regs[1];
            		$status   = $regs[2];
           			$pay_date = $regs[4]; # XXX if PAID
            		$stan     = $regs[5]; # XXX if PAID
            		$bcode    = $regs[6]; # XXX if PAID

					$info_data .= "INVOICE=$invoice:STATUS=OK\n";
					
				}
			}
			echo $info_data, "\n";
			
			if(isset($invoice) and $status == 'PAID')
			{

					$product_id=$invoice;

					$sql="select id_parent from credits_list where id_parent=".(int)$product_id;
					$ds->open($sql);
					if(!$ds->eof)
					{
						$transaction_id=transaction_add("epay",$stan,"credits",$product_id);
						credits_approve($product_id,$transaction_id);
						send_notification('credits_to_user',$product_id);
						send_notification('credits_to_admin',$product_id);
					}

					$sql="select id_parent from subscription_list where id_parent=".(int)$product_id;
					$ds->open($sql);
					if(!$ds->eof)
					{
						$transaction_id=transaction_add("epay",$stan,"subscription",$product_id);
						subscription_approve($product_id);
						send_notification('subscription_to_user',$product_id);
						send_notification('subscription_to_admin',$product_id);
					}

					$sql="select id from orders where id=".(int)$product_id;
					$ds->open($sql);
					if(!$ds->eof)
					{
						$transaction_id=transaction_add("epay",$stan,"order",$product_id);
						order_approve($product_id);
						commission_add($product_id);

						coupons_add(order_user($product_id));
						send_notification('neworder_to_user',$product_id);
						send_notification('neworder_to_admin',$product_id);
					}
			}		
    		
		}
		else
		{
			echo "ERR=Not valid CHECKSUM\n"; 
		}
	}
}
else
{
	$submit_url = epay_url;
	//$submit_url = "https://devep2.datamax.bg/ep2/epay2_demo/";



	$min        = $site_epay_account;
	//$invoice    = $product_type."-".$product_id; # XXX Invoice
	$invoice    = $product_id;
	$sum        = float_opt($product_total,2);                            # XXX Ammount
	$exp_date   = '01.08.2020';                       # XXX Expiration date
	$descr      = $product_name;                             # XXX Description

$data = <<<DATA
MIN={$min}
INVOICE={$invoice}
AMOUNT={$sum}
EXP_TIME={$exp_date}
DESCR={$descr}
DATA;

# XXX Packet:
#     (MIN or EMAIL)=     REQUIRED
#     INVOICE=            REQUIRED
#     AMOUNT=             REQUIRED
#     EXP_TIME=           REQUIRED
#     DESCR=              OPTIONAL

$ENCODED  = base64_encode($data);
$CHECKSUM = hmac('sha1', $ENCODED, $secret); # XXX SHA-1 algorithm REQUIRED

?>







<form method="post" name="process" id="process" action="<?=$submit_url?>">
<input type=hidden name=PAGE value="paylogin">
<input type=hidden name=ENCODED value="<?=$ENCODED?>">
<input type=hidden name=CHECKSUM value="<?=$CHECKSUM?>">
<input type=hidden name=CURRENCY value="<?=$currency_code1?>">
<input type=hidden name=URL_OK value="<?=surl.site_root."/members/payments_result.php?d=1"?>">
<input type=hidden name=URL_CANCEL value="<?=surl.site_root."/members/payments_result.php?d=2"?>">



</form>




<?

}



}
?>