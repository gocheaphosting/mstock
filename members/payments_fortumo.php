<?
include("../admin/function/db.php");
include("payments_settings.php");

if($site_fortumo_account!="")
{

  function check_signature($params_array, $secret) {
    ksort($params_array);

    $str = '';
    foreach ($params_array as $k=>$v) {
      if($k != 'sig') {
        $str .= "$k=$v";
      }
    }
    $str .= $secret;
    $signature = md5($str);

    return ($params_array['sig'] == $signature);
  }		

  // check that the request comes from Fortumo server
 if(!in_array($_SERVER['REMOTE_ADDR'],
    array('1.2.3.4', '2.3.4.5'))) {
    header("HTTP/1.0 403 Forbidden");
    die("Error: Unknown IP");
  }

  // check the signature
  $secret = $site_fortumo_password; // insert your secret between ''
  if(empty($secret) || !check_signature($_GET, $secret)) {
    header("HTTP/1.0 404 Not Found");
    die("Error: Invalid signature");
  }

  $sender = (int)$_GET['sender'];//phone num.
  $amount = (int)$_GET['amount'];//credit
  $cuid = (int)$_GET['cuid'];//resource i.e. user
  $payment_id = $_GET['payment_id'];//unique id

  //hint: find or create payment by payment_id
  //additional parameters: operator, price, user_share, country
    
  if(preg_match("/completed/i", $_GET['status'])) {
    // mark payment successful
    
    $sql="select login,name,lastname,address,city,zipcode,country from users where id_parent=".$cuid;
    $rs->open($sql);
    if(!$rs->eof)
    {
    	$sql="insert into credits_list (title,quantity,data,user,approved,payment,credits,expiration_date,subtotal,discount,taxes,total,billing_firstname,billing_lastname,billing_address,billing_city,billing_zip,billing_country) values ('".$amount." Credits','".$amount."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".$rs->row["login"]."',1,0,0,0,".(float)$_GET['price'].",0,0,".(float)$_GET['price'].",'".$rs->row["name"]."','".$rs->row["lastname"]."','".$rs->row["address"]."','".$rs->row["city"]."','".$rs->row["zipcode"]."','".$rs->row["country"]."')";
		$db->execute($sql);
		
		$sql="select id_parent from credits_list where user='".$rs->row["login"]."' order by id_parent desc";
		$ds->open($sql);
		$credits_id=$ds->row["id_parent"];
    	$transaction_id=transaction_add("fortumo",$payment_id,"credits",$credits_id);

		credits_approve($credits_id,$transaction_id);
		send_notification('credits_to_user',$credits_id);
		send_notification('credits_to_admin',$credits_id);
	}
  }

  // print out the reply
  echo('OK');
 
			
}
?>