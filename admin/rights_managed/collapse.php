<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_rightsmanaged");


$sql="select collapse from rights_managed_structure where id=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["collapse"]==0)
	{	
		$collapse=1;
	}
	else
	{
		$collapse=0;
	}
	
	$sql="update rights_managed_structure set collapse=".$collapse." where id=".(int)$_GET["id"];
	$db->execute($sql);
}

$db->close();

header("location:content.php?id=".$_GET["price"]);
?>