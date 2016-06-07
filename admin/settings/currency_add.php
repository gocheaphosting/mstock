<? include("../function/db.php");?>
<?include("../../members/payments_settings.php");?>
<?
//Check access
admin_panel_access("settings_currency");




$sql="insert into currency (name,code1,code2,activ) values ('".result($_POST["name"])."','".result($_POST["code"])."','".result($_POST["symbol"])."',0)";
$db->execute($sql);

$db->close();

header("location:currency.php");
?>