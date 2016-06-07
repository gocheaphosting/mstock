<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_prints");




if(isset($_GET["id"]) and isset($_GET["type"]))
{
	if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".(int)$_GET["id"]."_".(int)$_GET["type"]."_big.jpg"))
	{
		unlink($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".(int)$_GET["id"]."_".(int)$_GET["type"]."_big.jpg");
	}
	
	if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".(int)$_GET["id"]."_".(int)$_GET["type"]."_small.jpg"))
	{
		unlink($_SERVER["DOCUMENT_ROOT"].site_root."/content/prints/product".(int)$_GET["id"]."_".(int)$_GET["type"]."_small.jpg");
	}
}

$db->close();
	
header("location:content.php?id=".(int)$_GET["id"]);
?>
