<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_downloads");


$sql="select id from downloads";
$rs->open($sql);
while(!$rs->eof)
{
	if(isset($_POST["sel".$rs->row["id"]]))
	{
		$sql="delete from downloads where id=".$rs->row["id"];
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
	$return_url="../downloads/";
}

$db->close();

redirect($return_url);
?>
