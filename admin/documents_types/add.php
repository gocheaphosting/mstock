<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_documents");

$sql="insert into documents_types (title,description,enabled,buyer,seller,affiliate,filesize,priority) values ('New document','Document description',0,0,0,0,2,1)";
$db->execute($sql);

$db->close();

header("location:index.php");
?>