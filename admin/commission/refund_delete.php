<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_commission");




$sql="delete from commission where id=".(int)$_GET["id"];
$db->execute($sql);


$db->close();

header("location:index.php?d=2");
?>
