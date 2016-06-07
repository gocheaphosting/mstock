<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_couponstypes");



	$sql="delete from coupons_types  where id_parent=".(int)$_GET["id"];
	$db->execute($sql);
	
	$sql="delete from coupons  where coupon_id=".(int)$_GET["id"];
	$db->execute($sql);

$db->close();

header("location:index.php");
?>