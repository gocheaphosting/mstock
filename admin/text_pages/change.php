<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("pages_textpages");

$sql="select id_parent from pages";
$rs->open($sql);
while(!$rs->eof)
{	
	$siteinfo=0;
	if(isset($_POST["siteinfo".$rs->row["id_parent"]]))
	{
		$siteinfo=1;
	}
	
	$sql="update pages set priority=".(int)$_POST["priority".$rs->row["id_parent"]].",siteinfo=".$siteinfo." where id_parent=".$rs->row["id_parent"];
	$db->execute($sql);
	
	$rs->movenext();
}

unset($_SESSION["site_info_content"]);

$db->close();

header("location:index.php");
?>
