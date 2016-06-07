<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_blockedip");

$sql="insert into users_ip_blocked (ip,data) values ('".result($_POST["ip"])."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).")";
$db->execute($sql);

$db->close();

header("location:index.php");
?>