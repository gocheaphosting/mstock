<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_credits");


$sql="select id_parent from credits_list";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["sel".$rs->row["id_parent"]]))
	{
		$sql="delete from credits_list where id_parent=".$rs->row["id_parent"];
		$db->execute($sql);
		
		affiliate_delete_commission($rs->row["id_parent"],"credits");
	}
	$rs->movenext();
}


if(isset($_SERVER["HTTP_REFERER"]))
{
	$return_url=$_SERVER["HTTP_REFERER"];
}
else
{
	$return_url="../credits/";
}

$db->close();

redirect($return_url);
?>
