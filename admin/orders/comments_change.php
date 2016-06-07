<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_orders");


$sql="update orders set comments='".result($_POST["comments"])."' where  id=".(int)$_GET["id"];
$db->execute($sql);

$db->close();

header("location:order_content.php?id=".(int)$_GET["id"]);
?>