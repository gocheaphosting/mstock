<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("templates_caching");


if($_GET["id"]==1)
{
$smarty->clearCache(null,"header");
}

if($_GET["id"]==2)
{
$smarty->clearCache(null,"footer");
}

if($_GET["id"]==3)
{
$smarty->clearCache(null,"home");
}

if($_GET["id"]==4)
{
$smarty->clearCache(null,"item");
}

if($_GET["id"]==0)
{
$smarty->clearAllCache();
}

$db->close();
header("location:index.php");
?>