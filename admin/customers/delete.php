<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_customers");


$sql="select id_parent from users";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["sel".$rs->row["id_parent"]]))
	{
		$sql="delete from users where id_parent=".$rs->row["id_parent"];
		$db->execute($sql);
		
		$sql="delete from structure where id=".$rs->row["id_parent"];
		$db->execute($sql);
	}
	$rs->movenext();
}


if(isset($_SERVER["HTTP_REFERER"]))
{
	$return_url=$_SERVER["HTTP_REFERER"];
}
else
{
	$return_url="../customers/";
}

$db->close();

redirect($return_url);
?>
