<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_rightsmanaged");


$conditions="";
for($i=0;$i<7;$i++)
{
	if($i!=0)
	{
		$conditions.="-";
	}
	
	if(isset($_POST["condition".$i]))
	{	
		$conditions.=(int)$_POST["condition".$i];
	}
	else
	{
		$conditions.="0";
	}
}

$sql="update rights_managed_structure set conditions='".$conditions."' where id=".(int)$_GET["id_element"];
$db->execute($sql);

$db->close();

header("location:content.php?id=".$_GET["id"]);
?>