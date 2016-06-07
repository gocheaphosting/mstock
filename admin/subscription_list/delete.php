<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_subscription");


$sql="select id_parent from subscription_list";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["sel".$rs->row["id_parent"]]))
	{
		$sql="delete from subscription_list where id_parent=".$rs->row["id_parent"];
		$db->execute($sql);
		
		$sql="delete from downloads where subscription_id=".$rs->row["id_parent"];
		$db->execute($sql);
		
		affiliate_delete_commission($rs->row["id_parent"],"subscription");
	}
	$rs->movenext();
}


if(isset($_SERVER["HTTP_REFERER"]))
{
	$return_url=$_SERVER["HTTP_REFERER"];
}
else
{
	$return_url="../subscription_list/";
}

$db->close();

redirect($return_url);
?>
