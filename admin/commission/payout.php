<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_commission");

include("../inc/begin.php");

//Payments settings
include($_SERVER["DOCUMENT_ROOT"].site_root."/members/payments_settings.php");

$url="refund_send.php";
include("payout_content.php");

include("../inc/end.php");
?>


