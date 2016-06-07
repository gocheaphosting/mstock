<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_rightsmanaged");




$sql="insert into rights_managed_structure (id_parent,types,title,adjust,price,price_id,group_id,option_id,conditions,collapse) values (0,0,'".result($_POST["title"])."','',0,".(int)$_GET["id"].",0,0,'',0)";
$db->execute($sql);

$db->close();

header("location:content.php?id=".$_GET["id"]);
?>