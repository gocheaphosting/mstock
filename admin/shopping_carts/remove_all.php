<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_carts");


$sql="delete from carts";
$db->execute($sql);
		
$sql="delete from carts_content";
$db->execute($sql);



$return_url="../shopping_carts/";

$db->close();

redirect($return_url);
?>
