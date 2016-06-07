<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);


$id=(int)@$_REQUEST['id'];
$id2=(int)@$_REQUEST['id2'];


$cart_id=shopping_cart_id();

if($id>0)
{
	$sql="update carts_content set item_id=".$id2." where id=".$id." and id_parent=".$cart_id;
}
else
{
	$sql="update carts_content set prints_id=".(-1*$id2).",quantity=1,option1_id=0,option1_value='',option2_id=0,option2_value='',option3_id=0,option3_value='',option4_id=0,option4_value='',option5_id=0,option5_value='',option6_id=0,option6_value='',option7_id=0,option7_value='',option8_id=0,option8_value='',option9_id=0,option9_value='',option10_id=0,option10_value='' where id=".(-1*$id)." and id_parent=".$cart_id;
}
$db->execute($sql);

unset($_SESSION["box_shopping_cart"]);
unset($_SESSION["box_shopping_cart_lite"]);

include("shopping_cart_content.php");

$db->close();
?>
