<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

$id=(int)@$_REQUEST['id'];
$i=(int)@$_REQUEST['i'];
$option_id=(int)@$_REQUEST['option_id'];
$option_value=result($_REQUEST['option_value']);

$cart_id=shopping_cart_id();

$sql="update carts_content set option".$i."_id=".$option_id.",option".$i."_value='".$option_value."' where id=".$id." and id_parent=".$cart_id;
$db->execute($sql);

unset($_SESSION["box_shopping_cart"]);
unset($_SESSION["box_shopping_cart_lite"]);

include("shopping_cart_content.php");

$db->close();
?>
