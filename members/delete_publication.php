<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){exit;}?>
<? include("../admin/function/upload.php");?>
<?if($global_settings["userupload"]==0){exit;}?>
<?




$sql="select module_table from structure where id=".(int)$_GET["id"];
$ds->open($sql);
if(!$ds->eof)
{
	if($ds->row["module_table"]==30){$table="photos";}
	if($ds->row["module_table"]==31){$table="videos";}
	if($ds->row["module_table"]==52){$table="audio";}
	if($ds->row["module_table"]==53){$table="vector";}
				
	$sql="select id_parent from ".$table." where (userid=".(int)$_SESSION["people_id"]." or author='".result($_SESSION["people_login"])."') and id_parent=".(int)$_GET["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		publication_delete((int)$_GET["id"]);
	}
}

$db->close();

header("location:upload.php");
?>