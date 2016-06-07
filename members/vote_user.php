<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

if(!isset($_SESSION["people_id"]) and $global_settings["users_rating_limited"])
{
	exit();
}

$sql="select ip,id from voteitems_users where ip='".result($_SERVER["REMOTE_ADDR"])."' and id=".(int)$_REQUEST["id"];
$ds->open($sql);
if($ds->eof)
{
	$sql="insert into voteitems_users (id,ip,vote) values (".(int)$_REQUEST["id"].",'".result($_SERVER["REMOTE_ADDR"])."',".(float)$_REQUEST["vote"].")";
	$ds->open($sql);
}

$item_rating=0.00;
$item_count=0;
$sql="select sum(vote) as sum_vote,count(ip) as count_user from voteitems_users where id=".(int)$_REQUEST["id"];
$dr->open($sql);
if(!$dr->eof)
{
	if($dr->row["count_user"]!=0)
	{
		$item_rating=$dr->row["sum_vote"]/$dr->row["count_user"];
		$item_count=$dr->row["count_user"];
		
		$sql="update users set rating=".$item_rating." where id_parent=".(int)$_REQUEST["id"];
		$db->execute($sql);
	}
}


$db->close();
?>