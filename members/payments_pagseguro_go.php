<?
include("../admin/function/db.php");
include("payments_settings.php");

header('Content-Type: text/html; charset=ISO-8859-1');

define('TOKEN', $site_pagseguro_password);

class PagSeguroNpi {
	
	private $timeout = 20; // Timeout em segundos
	
	public function notificationPost() {
		$postdata = 'Comando=validar&Token='.TOKEN;
		foreach ($_POST as $key => $value) {
			$valued    = $this->clearStr($value);
			$postdata .= "&$key=$valued";
		}
		return $this->verify($postdata);
	}
	
	private function clearStr($str) {
		if (!get_magic_quotes_gpc()) {
			$str = addslashes($str);
		}
		return $str;
	}
	
	private function verify($data) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://pagseguro.uol.com.br/pagseguro-ws/checkout/NPI.jhtml");
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$result = trim(curl_exec($curl));
		curl_close($curl);
		return $result;
	}

}

if (count($_POST) > 0) {
	
	// POST recebido, indica que é a requisição do NPI.
	$npi = new PagSeguroNpi();
	$result = $npi->notificationPost();
	
	$transacaoID = isset($_POST['TransacaoID']) ? $_POST['TransacaoID'] : '';
	
	if ($result == "VERIFICADO") {
	
				$transaction_id=transaction_add("pagseguro",result($_POST['TransacaoID']),result($_POST["ProdDescricao_1"]),(int)$_POST["ProdID_1"]);
				
				$id=(int)$_POST["ProdID_1"];

				if($_POST["ProdDescricao_1"]=="credits")
				{
					credits_approve($id,$transaction_id);
					send_notification('credits_to_user',$id);
					send_notification('credits_to_admin',$id);
				}

				if($_POST["ProdDescricao_1"]=="subscription")
				{
					subscription_approve($id);
					send_notification('subscription_to_user',$id);
					send_notification('subscription_to_admin',$id);
				}

				if($_POST["ProdDescricao_1"]=="order")
				{
					order_approve($id);
					commission_add($id);

					coupons_add(order_user($id));
					send_notification('neworder_to_user',$id);
					send_notification('neworder_to_admin',$id);
				}
				
				
				
	} else if ($result == "FALSO") {
		//O post não foi validado pelo PagSeguro.
	} else {
		//Erro na integração com o PagSeguro.
	}
	
} else {

}


$db->close();
?>