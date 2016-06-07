<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("catalog_upload");


	$sql="select * from galleries_photos where id=".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$sql="delete from galleries_photos where id=".$rs->row["id"];
		$db->execute($sql);
			
		@unlink($_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".(int)$_GET["gallery_id"]."/".$rs->row["photo"]);	
		@unlink($_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".(int)$_GET["gallery_id"]."/thumb".$rs->row["id"].".jpg");	
	}


header("location:index.php?d=7&id=".(int)$_GET["gallery_id"]);
?>