<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_prints");

$sql="delete from prints where id_parent=".(int)$_GET["id"];
$db->execute($sql);
		
$sql="delete from prints_items where printsid=".(int)$_GET["id"];
$db->execute($sql);

//Delete photos
for($i=1;$i<6;$i++)
{
	if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".(int)$_GET["id"]."_".$i."_big.jpg"))
	{
		unlink($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".(int)$_GET["id"]."_".$i."_big.jpg");
	}
	
	if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".(int)$_GET["id"]."_".$i."_small.jpg"))
	{
		unlink($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".(int)$_GET["id"]."_".$i."_small.jpg");
	}
}

$db->close();
	
header("location:index.php");
?>
