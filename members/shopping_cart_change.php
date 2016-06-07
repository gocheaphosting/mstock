<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

$id=(int)@$_REQUEST['id'];
$qty=(int)@$_REQUEST['qty'];

$cart_id=shopping_cart_id();

$sql="update carts_content set quantity=".$qty." where id=".$id." and id_parent=".$cart_id;
$db->execute($sql);

unset($_SESSION["box_shopping_cart"]);
unset($_SESSION["box_shopping_cart_lite"]);

include("shopping_cart_content.php");

$db->close();
?>
