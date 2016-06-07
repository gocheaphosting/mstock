<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_video");

$sql="insert into video_ratio (name,width,height) values ('".result($_POST["new"])."',".(int)$_POST["width"].",".(int)$_POST["height"].")";
$db->execute($sql);


$db->close();


header("location:index.php?d=2");
?>