<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("catalog_upload");


$sql="select id from galleries where id=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
	$sql="delete from galleries where id=".(int)$_GET["id"];
	$db->execute($sql);

	$sql="delete from galleries_photos where id_parent=".(int)$_GET["id"];
	$db->execute($sql);

	if((int)$_GET["id"]!=0 and file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".(int)$_GET["id"]))
	{
		$dir = opendir ($_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".(int)$_GET["id"]);
		while ($file = readdir ($dir)) 
		{
			if($file <> "." && $file <> "..")
			{
				@unlink($_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".(int)$_GET["id"]."/".$file);		
			}
		}
	
		@rmdir($_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".(int)$_GET["id"]);
	}
}


header("location:index.php?d=6");
?>