<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("settings_models");

//If the model is new
if(isset($_GET["id"]) and (int)$_GET["id"]!=0)
{
	$sql="update models set name='".result($_POST["name"])."',description='".result($_POST["description"])."',user='".result($_POST["user"])."' where id_parent=".(int)$_GET["id"];
	$db->execute($sql);
	
	$id=(int)$_GET["id"];
}
else
{	
	$sql="insert into models (name,user,description) values ('".result($_POST["name"])."','".result($_POST["user"])."','".result($_POST["description"])."')";
	$db->execute($sql);
	
	$id=0;
	$sql="select id_parent from models where user='".result($_POST["user"])."' order by id_parent desc";
	$ds->open($sql);
	if(!$ds->eof)
	{
		$id=$ds->row["id_parent"];
	}
}

//Upload files
$swait=model_upload($id);

$db->close();

redirect_file("index.php",$swait);
?>