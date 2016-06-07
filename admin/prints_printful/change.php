<? 
include("../function/db.php");

//Check access
admin_panel_access("settings_printful");

$sql="update settings set svalue='".result($_POST["printful_api"])."' where setting_key='printful_api'";
$db->execute($sql);

$sql="update settings set svalue=".(int)$_POST["printful_order_id"]." where setting_key='printful_order_id'";
$db->execute($sql);

$sql="update settings set svalue='".result($_POST["printful_mode"])."' where setting_key='printful_mode'";
$db->execute($sql);

$db->close();

header("location:index.php?d=1");
?>