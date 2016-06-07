<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

if(!isset($_SESSION["people_id"]) and $global_settings["auth_rating"])
{
	exit();
}

$id=(int)@$_REQUEST["id"];
$score=(float)@$_REQUEST["score"];

$sql="select id_parent from support_tickets where id=".$id;
$rs->open($sql);
if(!$rs->eof)
{
	$sql="select id from support_tickets where id=".$rs->row["id_parent"]." and user_id=".(int)$_SESSION["people_id"];
	$ds->open($sql);
	if(!$ds->eof)
	{
		$sql="update support_tickets set rating=".$score." where id=".$id;
		$db->execute($sql);
	}
}

$db->close();
?>