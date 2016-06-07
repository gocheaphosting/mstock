<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_orders");

$sql="select id from orders";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["sel".$rs->row["id"]]))
	{
		$sql="delete from orders where id=".$rs->row["id"];
		$db->execute($sql);

		$sql="delete from orders_content where id_parent=".$rs->row["id"];
		$db->execute($sql);

		$sql="delete from commission where orderid=".$rs->row["id"];
		$db->execute($sql);
		
		$sql="delete from downloads where order_id=".$rs->row["id"];
		$db->execute($sql);

		affiliate_delete_commission($rs->row["id"],"orders");
	}
	$rs->movenext();
}





if(isset($_SERVER["HTTP_REFERER"]))
{
	$return_url=$_SERVER["HTTP_REFERER"];
}
else
{
	$return_url="index.php";
}

$db->close();

redirect($return_url);
?>
