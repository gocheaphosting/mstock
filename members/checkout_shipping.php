<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =& new JsHttpRequest($mtg);

$shipping=(float)$_REQUEST["shipping"];
$type=(int)$_REQUEST["type"];

$_SESSION["product_shipping"]=$shipping;
$_SESSION["product_shipping_type"]=$type;

$taxes_info["included"]=0;

$sql="select taxes from shipping where id=".$type;
$dr->open($sql);
if(!$dr->eof)
{
	if($dr->row["taxes"]==1)
	{
		order_taxes_calculate($_SESSION["product_subtotal"]+$_SESSION["product_shipping"],false,"order");
		$product_tax=$taxes_info["total"];
		$_SESSION["product_tax"]=$product_tax;
	}
	else
	{
		order_taxes_calculate($_SESSION["product_subtotal"],false,"order");
		$product_tax=$taxes_info["total"];
		$_SESSION["product_tax"]=$product_tax;
	}
}


$_SESSION["product_total"]=$_SESSION["product_subtotal"]+$_SESSION["product_shipping"]+$_SESSION["product_tax"]*$taxes_info["included"]-$_SESSION["product_discount"];

$GLOBALS['_RESULT'] = array(
  "shipping"     => currency(1).float_opt($_SESSION["product_shipping"],2)." ".currency(2),
  "total"     => "<b>".word_lang("total").":</b> <span class='price'><b>".currency(1).float_opt($_SESSION["product_total"],2)." ".currency(2)."</b></span>",
  "taxes"     => currency(1).float_opt($_SESSION["product_tax"],2)." ".currency(2),
); 
?>
