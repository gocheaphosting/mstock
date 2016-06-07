<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_models");

$sql="select id_parent,name from models";
$rs->open($sql);
while(!$rs->eof)
{		
	$sql="update models set name='".result($_POST["title".$rs->row["id_parent"]])."' where id_parent=".$rs->row["id_parent"];
	$db->execute($sql);
	$rs->movenext();
}

$db->close();

header("location:index.php");
?>
