<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_orders");


$sql="update downloads set tlimit=0,data=".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))+3600*24*$global_settings["download_expiration"])." where id=".(int)$_GET["id"];
$db->execute($sql);

$db->close();

header("location:index.php");
?>