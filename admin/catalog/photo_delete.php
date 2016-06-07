<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_catalog");

$sql="select server1,url_jpg,url_png,url_gif,url_raw,url_tiff,url_eps from photos where id_parent=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
	if(file_exists($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".(int)$_GET["id"]."/".$rs->row["url_".result($_GET["file"])]) and $rs->row["url_".result($_GET["file"])]!="")
	{
		@unlink($DOCUMENT_ROOT.server_url($rs->row["server1"])."/".(int)$_GET["id"]."/".$rs->row["url_".result($_GET["file"])]);
	}
	
	$sql="update photos set url_".result($_GET["file"])."='' where id_parent=".(int)$_GET["id"];
	$db->execute($sql);
	

}

$db->close();

header("location:content.php?id=".(int)$_GET["id"]);
?>

