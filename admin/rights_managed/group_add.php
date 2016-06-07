<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_rightsmanaged");



$sql="select id,title from rights_managed_groups where id=".(int)$_POST["group"];
$rs->open($sql);
if(!$rs->eof)
{
	$sql="insert into rights_managed_structure (id_parent,types,title,adjust,price,price_id,group_id,option_id,conditions,collapse) values (".(int)$_GET["step"].",1,'".$rs->row["title"]."','',0,".(int)$_GET["id"].",".$rs->row["id"].",0,'',0)";
	$db->execute($sql);
	
	$sql="select id from rights_managed_structure where title='".$rs->row["title"]."' and id_parent=".(int)$_GET["step"]." order by id desc";
	$ds->open($sql);
	$id=$ds->row["id"];
	
	$sql="select * from rights_managed_options where id_parent=".(int)$_POST["group"];
	$ds->open($sql);
	while(!$ds->eof)
	{
		$sql="insert into rights_managed_structure (id_parent,types,title,adjust,price,price_id,group_id,option_id,conditions,collapse) values (".$id.",2,'".$ds->row["title"]."','".$ds->row["adjust"]."',".$ds->row["price"].",".(int)$_GET["id"].",".$rs->row["id"].",".$ds->row["id"].",'',0)";
		$db->execute($sql);
		
		$ds->movenext();
	}
}

$db->close();

header("location:content.php?id=".$_GET["id"]);
?>