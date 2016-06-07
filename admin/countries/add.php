<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_countries");


$sql="insert into countries (name,priority,activ) values ('".result($_POST["country"])."',1,1)";
$db->execute($sql);

header("location:index.php");
?>
