<? include("../function/db.php");?><?//Check accessadmin_panel_access("settings_storage");
$sql="select id from filestorage order by id desc";
$rs->open($sql);
$id=$rs->row["id"]+1;

$sql="insert into filestorage (id,url,types,name) values (".$id.",'/content".$id."',0,'Local server')";
$db->execute($sql);


if(!file_exists($DOCUMENT_ROOT."/content".$id)){mkdir($DOCUMENT_ROOT."/content".$id);@copy($DOCUMENT_ROOT."/content/.htaccess",$DOCUMENT_ROOT."/content".$id."/.htaccess");}unset($_SESSION["site_server_activ"]);unset($_SESSION["site_server"]);$db->close();header("location:index.php?d=2");
?>