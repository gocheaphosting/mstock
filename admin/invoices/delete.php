<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_payments");


$sql="select id from invoices";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["sel".$rs->row["id"]]))
	{
		$sql="delete from invoices where id=".$rs->row["id"];
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
	$return_url="../invoices/";
}

$db->close();

redirect($return_url);
?>
