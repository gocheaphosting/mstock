<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_customers");




$sql="select ".result3($_GET["type"])." from users where id_parent=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row[result3($_GET["type"])]!="")
	{
		unlink($_SERVER["DOCUMENT_ROOT"].$rs->row[result3($_GET["type"])]);
	}
	
	$sql="update users set ".result3($_GET["type"])."='' where id_parent=".(int)$_GET["id"];
	$db->execute($sql);
}

$db->close();
	
header("location:content.php?id=".(int)$_GET["id"]);
?>
