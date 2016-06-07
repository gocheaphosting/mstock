<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("affiliates_commission");

include("../inc/begin.php");

//Payments settings
include($_SERVER["DOCUMENT_ROOT"].site_root."/members/payments_settings.php");

$url="payout_send.php";
include("../commission/payout_content.php");

include("../inc/end.php");
?>


