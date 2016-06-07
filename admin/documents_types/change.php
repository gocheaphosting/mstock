<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_documents");

$sql="select * from documents_types order by priority";
$rs->open($sql);

while(!$rs->eof)
{
	$sql="update documents_types set title='".result($_POST["title".$rs->row["id"]])."',description='".result($_POST["description".$rs->row["id"]])."',filesize=".(int)$_POST["filesize".$rs->row["id"]].",priority=".(int)$_POST["priority".$rs->row["id"]].",enabled=".(int)@$_POST["enabled".$rs->row["id"]].",buyer=".(int)@$_POST["buyer".$rs->row["id"]].",seller=".(int)@$_POST["seller".$rs->row["id"]].",affiliate=".(int)@$_POST["affiliate".$rs->row["id"]]." where id=".$rs->row["id"];
	$db->execute($sql);
	
	$rs->movenext();
}

$db->close();

header("location:index.php");
?>