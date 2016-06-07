<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_checkout");

$sql="insert into terms (types,title,priority,page_id) values (1,'New terms and conditions','1','0')";
$db->execute($sql);


$db->close();

header("location:index.php");
?>
