<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_payout");


$sql="insert into banks (title) values ('".result($_POST["new"])."')";
$db->execute($sql);

$db->close();

header("location:index.php?d=3");
?>