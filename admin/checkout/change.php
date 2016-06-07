<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_checkout");

$checkout_settings=array('checkout_order_billing','checkout_order_shipping','checkout_credits_billing','checkout_subscription_billing');

for($i=0;$i<count($checkout_settings);$i++)
{
	$sql="update settings set activ='".(int)@$_POST[$checkout_settings[$i]]."' where setting_key='".$checkout_settings[$i]."'";
	$db->execute($sql);
}

$db->close();

header("location:index.php");
?>
