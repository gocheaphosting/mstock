<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("templates_caching");

$sql="select * from caching";
$rs->open($sql);
while(!$rs->eof)
{
	$time_refresh=(int)$_POST["time_refresh".$rs->row["id"]];
	if(!isset($_POST["ch".$rs->row["id"]]))
	{
		$time_refresh=-1;
	}

	$sql="update caching set time_refresh=".$time_refresh." where id=".$rs->row["id"];
	$db->execute($sql);

	$rs->movenext();
}

unset($_SESSION["site_cache_header"]);
unset($_SESSION["site_cache_footer"]);
unset($_SESSION["site_cache_home"]);
unset($_SESSION["site_cache_item"]);
unset($_SESSION["site_cache_catalog"]);
unset($_SESSION["site_cache_components"]);
unset($_SESSION["site_cache_stats"]);

$db->close();

header("location:index.php");
?>