<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_sellercategories");

$sql="select id_parent,priority,name from user_category order by priority";
$rs->open($sql);
while(!$rs->eof)
{
	if(result($_POST["title".$rs->row["id_parent"]])!=$rs->row["name"])
	{
		$sql="update users set category='".result($_POST["title".$rs->row["id_parent"]])."' where category='".$rs->row["name"]."'";
		$db->execute($sql);
	}
	

		
	
	$sql="update user_category set name='".result($_POST["title".$rs->row["id_parent"]])."',priority=".(int)$_POST["priority".$rs->row["id_parent"]].",percentage=".(float)$_POST["percentage".$rs->row["id_parent"]].",percentage_prints=".(float)$_POST["percentage_prints".$rs->row["id_parent"]].",percentage_subscription=".(float)$_POST["percentage_subscription".$rs->row["id_parent"]].",percentage_type=".(int)$_POST["percentage_type".$rs->row["id_parent"]].",percentage_prints_type=".(int)$_POST["percentage_prints_type".$rs->row["id_parent"]].",percentage_subscription_type=".(int)$_POST["percentage_subscription_type".$rs->row["id_parent"]]." where id_parent=".$rs->row["id_parent"];
	$db->execute($sql);
	$rs->movenext();
}

$db->close();

header("location:index.php");
?>
