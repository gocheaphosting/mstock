<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("affiliates_payout");




$sql="delete from affiliates_signups where data=".(int)$_GET["data"]." and aff_referal=".(int)$_GET["aff_referal"];
$db->execute($sql);


$db->close();

header("location:payout.php");
?>
