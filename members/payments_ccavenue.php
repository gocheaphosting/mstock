<?if(!defined("site_root")){exit();}?>
<?
if($site_ccavenue_account!="")
{

 include('../admin/plugins/ccavenue/adler32.php');
 include('../admin/plugins/ccavenue/Aes.php');


$merchant_id=$site_ccavenue_account;  // Merchant id(also User_Id) 
$amount=float_opt($product_total,2);            // your script should substitute the amount here in the quotes provided here
$order_id=$product_type."-".$product_id;        //your script should substitute the order description here in the quotes provided here
$url=surl.site_root."/members/payments_ccavenue_go.php";         //your redirect URL where your customer will be redirected after authorisation from CCAvenue


$buyer_info=array();
get_buyer_info($_SESSION["people_id"],$product_id,$product_type);




$billing_cust_name=$buyer_info["name"]." ".$buyer_info["lastname"];
$billing_cust_address=$buyer_info["billing_address"];
$billing_cust_country=$buyer_info["billing_country"];
$billing_cust_state=$buyer_info["billing_state"];
$billing_city=$buyer_info["billing_city"];
$billing_zip=$buyer_info["billing_zipcode"];
$billing_cust_tel=$buyer_info["telephone"];
$billing_cust_email=$buyer_info["email"];
$delivery_cust_name="";
$delivery_cust_address="";
$delivery_cust_country="";
$delivery_cust_state="";
$delivery_city="";
$delivery_zip="";
$delivery_cust_tel="";
$delivery_cust_notes="";





$working_key=$site_ccavenue_password;	//Put in the 32 bit alphanumeric key in the quotes provided here.

$access_code=$site_ccavenue_password2;
$currency_code=$currency_code1;
$vURLSubDomain = "secure";  //"secure" for live payments | "test" for sandbox/testing payments.

$checksum=getchecksum($merchant_id,$amount,$order_id,$url,$working_key); // Method to generate checksum

$merchant_data= 'currency='.$currency_code.'&merchant_id='.$merchant_id.'&amount='.$amount.'&order_id='.$order_id.'&redirect_url='.$url.'&billing_name='.$billing_cust_name.'&billing_address='.$billing_cust_address.'&billing_country='.$billing_cust_country.'&billing_state='.$billing_cust_state.'&billing_city='.$billing_city.'&billing_zip='.$billing_zip.'&billing_tel='.$billing_cust_tel.'&billing_email='.$billing_cust_email.'&delivery_name='.$delivery_cust_name.'&delivery_address='.$delivery_cust_address.'&delivery_country='.$delivery_cust_country.'&delivery_state='.$delivery_cust_state.'&delivery_city='.$delivery_city.'&delivery_zip='.$delivery_zip.'&delivery_tel='.$delivery_cust_tel.'&billing_cust_notes='.$delivery_cust_notes.'&Checksum='.$checksum;


$encrypted_data=encrypt($merchant_data,$working_key); // Method for encrypting the data.
?>

<form method="post" name="process" id="process" action="https://<?php echo $vURLSubDomain;?>.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
<?php
echo "<input type=hidden name=encRequest value=$encrypted_data>";
echo "<input type=hidden name=Merchant_Id value=$merchant_id>";
echo "<input type=hidden name=access_code value=$access_code>";

?>

</form>

<?
}
?>