<? include("../function/db.php");?>
<?include("../../members/payments_settings.php");?>
<?
//Check access
admin_panel_access("settings_payments");



$sql="select * from gateway_ccbill where subscription<>0 or credits<>0";
$rs->open($sql);
while(!$rs->eof)
{

if(isset($_POST["product".$rs->row["subscription"]."_".$rs->row["credits"]]))
{
$sql="update gateway_clickbank set product_id='".result($_POST["product".$rs->row["subscription"]."_".$rs->row["credits"]])."' where subscription=".$rs->row["subscription"]." and credits=".$rs->row["credits"];
$db->execute($sql);
}

$rs->movenext();
}

$db->close();

header("location:payments.php");
?>