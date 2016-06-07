<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

$cart_id=shopping_cart_id();

$sql="delete from carts_content where id_parent=".$cart_id;
$db->execute($sql);

unset($_SESSION["box_shopping_cart"]);
unset($_SESSION["box_shopping_cart_lite"]);

include("shopping_cart_content.php");

$db->close();
?>
