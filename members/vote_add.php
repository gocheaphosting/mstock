<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

if(!isset($_SESSION["people_id"]) and $global_settings["auth_rating"])
{
	exit();
}

$sql="select ip,id from voteitems where ip='".result($_SERVER["REMOTE_ADDR"])."' and id=".(int)$_REQUEST["id"];
$ds->open($sql);
if($ds->eof)
{
	$sql="insert into voteitems (id,ip,vote) values (".(int)$_REQUEST["id"].",'".result($_SERVER["REMOTE_ADDR"])."',".(float)$_REQUEST["vote"].")";
	$ds->open($sql);
}

$item_rating=0.00;
$item_count=0;
$sql="select sum(vote) as sum_vote,count(ip) as count_user from voteitems where id=".(int)$_REQUEST["id"];
$dr->open($sql);
if(!$dr->eof)
{
	if($dr->row["count_user"]!=0)
	{
		$item_rating=$dr->row["sum_vote"]/$dr->row["count_user"];
		$item_count=$dr->row["count_user"];
		
		$module_table=0;
		$sql="select module_table from structure where id=".(int)$_REQUEST["id"];
		$rs->open($sql);
		if(!$rs->eof)
		{
			$module_table=$rs->row["module_table"];
		}
		
		if($module_table==30)
		{
			$sql="update photos set rating=".$item_rating." where id_parent=".(int)$_REQUEST["id"];
			$db->execute($sql);
		}
		
		if($module_table==31)
		{
			$sql="update videos set rating=".$item_rating." where id_parent=".(int)$_REQUEST["id"];
			$db->execute($sql);
		}
		
		if($module_table==52)
		{
			$sql="update audio set rating=".$item_rating." where id_parent=".(int)$_REQUEST["id"];
			$db->execute($sql);
		}
		
		if($module_table==53)
		{
			$sql="update vector set rating=".$item_rating." where id_parent=".(int)$_REQUEST["id"];
			$db->execute($sql);
		}
	}
}

$rating_text="";
for($j=1;$j<6;$j++)
{
	$tt="2";
	if($j<=$item_rating or $j-$item_rating<=0.25){$tt="1";}
	if($j>$item_rating and $j-$item_rating>0.25 and $j-$item_rating<0.75){$tt="3";}
	$rating_text.="<a href=\"javascript:doVote('".strval($j)."');\"><img src='".site_root."/".$site_template_url."images/rating".$tt.".gif' width='11' id='rating".$j."' onMouseover='mrating(".$j.");' onMouseout='mrating2(".$item_rating.");'  height='11' class='rating' border='0'></a>";
}


echo($rating_text);
$db->close();
?>