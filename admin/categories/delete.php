<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("catalog_categories");

$res_id=array();
$nlimit=0;
get_included_categories((int)$_GET["id"]);
$res_id[]=(int)$_GET["id"];

for($i=0;$i<count($res_id);$i++)
{
	if(!$demo_mode)
	{
		delete_category($res_id[$i],0);
	}
}

$smarty->clearCache(null,"buildmenu");

$db->close();

header("location:index.php");
?>