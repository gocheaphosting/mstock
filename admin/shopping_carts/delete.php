<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_carts");


$sql="select id from carts";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["sel".$rs->row["id"]]))
	{
		$sql="delete from carts where id=".$rs->row["id"];
		$db->execute($sql);
		
		$sql="delete from carts_content where id_parent=".$rs->row["id"];
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
	$return_url="../shopping_carts/";
}

$db->close();

redirect($return_url);
?>
