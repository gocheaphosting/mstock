<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("templates_skins");




$sql="insert into templates (name,url,activ,home,shome) values ('".result($_POST["name"])."','".result($_POST["url"])."',0,3,1)";
$db->execute($sql);

$db->close();


header("location:skins.php");
?>