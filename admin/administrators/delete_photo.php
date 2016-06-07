<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_administrators");




$sql="select photo from people where id=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["photo"]!="")
	{
		unlink($_SERVER["DOCUMENT_ROOT"].$rs->row["photo"]);
	}
	
	$sql="update people set photo='' where id=".(int)$_GET["id"];
	$db->execute($sql);
}

$db->close();
	
header("location:content.php?id=".(int)$_GET["id"]);
?>
