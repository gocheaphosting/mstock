<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("catalog_categories");

//If the category is new
$id=0;
if(isset($_GET["id"])){$id=(int)$_GET["id"];}

if($id!=(int)$_POST["category"])
{
	$swait=add_update_category($id,0,0,0);
}
else
{
	$swait=false;
}
		
$smarty->clearCache(null,"buildmenu");
if($id!=0)
{
	category_url($id);
}

$db->close();

redirect_file("index.php",$swait);
?>