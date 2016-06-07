<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_orders");

$sql="select userid,types,types_id,rates,total,data,aff_referal from affiliates_signups where total>0 ";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["sel".$rs->row["userid"]."_".$rs->row["data"]."_".$rs->row["types_id"]]))
	{
		$sql="delete from affiliates_signups where userid=".$rs->row["userid"]." and data=".$rs->row["data"]." and types_id=".$rs->row["types_id"];
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
	$return_url="index.php";
}

$db->close();

redirect($return_url);
?>
