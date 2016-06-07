<? include("../function/db.php");?>
<?include("../../members/payments_settings.php");?>
<?
//Check access
admin_panel_access("settings_currency");




$sql="delete from currency where id=".(int)$_GET["id"];
$db->execute($sql);

$db->close();

header("location:currency.php");
?>