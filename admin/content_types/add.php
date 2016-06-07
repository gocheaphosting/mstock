<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_content_types");


$sql="insert into content_type (priority,name) values (".(int)$_POST["priority"].",'".result($_POST["title"])."')";
$db->execute($sql);

$db->close();

header("location:index.php");
?>
