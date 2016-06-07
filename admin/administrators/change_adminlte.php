<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_administrators");


$sql="select id, title, left_right, activ, priority from templates_admin_home order by left_right,priority";
$rs->open($sql);
while(!$rs->eof)
{
	$sql="update templates_admin_home set activ=".(int)@$_POST["active".$rs->row["id"]].",priority=".(int)$_POST["priority".$rs->row["id"]].",left_right=".(int)$_POST["left_right".$rs->row["id"]]." where id=".$rs->row["id"];
	$db->execute($sql);
		
	$tab_name=preg_replace("/[^a-z_]/i","",strtolower(str_replace(" ","_",$rs->row["title"])));

	setcookie("delete_".$tab_name, "", time()-3600,"/");

	$rs->movenext();
}

$db->close();
	
header("location:select_theme.php");
?>
