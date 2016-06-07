<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_licenses");


$sql="select id_parent,priority,name from licenses order by priority";
$rs->open($sql);
while(!$rs->eof)
{	
	$sql="update licenses set name='".result($_POST["title".$rs->row["id_parent"]])."',priority=".(int)$_POST["priority".$rs->row["id_parent"]]." where id_parent=".$rs->row["id_parent"];
	$db->execute($sql);
	$rs->movenext();
}

$db->close();

header("location:index.php");
?>
