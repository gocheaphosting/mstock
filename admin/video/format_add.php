<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_video");


$sql="insert into video_format (name) values ('".result($_POST["new"])."')";
$db->execute($sql);



$db->close();

header("location:index.php?d=1");
?>