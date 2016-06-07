<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

if(!isset($_SESSION["people_id"]) and $global_settings["auth_rating"])
{
	exit();
}

$rating_text="";

$sql="select ip,id from voteitems2 where ip='".result($_SERVER["REMOTE_ADDR"])."' and id=".(int)$_REQUEST["id"];
$ds->open($sql);
if($ds->eof)
{
	$sql="insert into voteitems2 (id,ip,vote) values (".(int)$_REQUEST["id"].",'".result($_SERVER["REMOTE_ADDR"])."',".(int)$_REQUEST["vote"].")";
	$ds->open($sql);

		
	$module_table=0;
	$sql="select module_table from structure where id=".(int)$_REQUEST["id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$module_table=$rs->row["module_table"];
	}
	
	if($_REQUEST["vote"]>0)
	{
		$com="vote_like=vote_like+1";
		$rating_text=word_lang("like")." ";
	}
	else
	{
		$com="vote_dislike=vote_dislike+1";
		$rating_text=word_lang("dislike")." ";
	}
		
	if($module_table==30)
	{
		$ptable="photos";	
	}
		
	if($module_table==31)
	{
		$ptable="videos";
	}
		
	if($module_table==52)
	{
		$ptable="audio";
	}
		
	if($module_table==53)
	{
		$ptable="vector";
	}
	
	$sql="update ".$ptable." set ".$com." where id_parent=".(int)$_REQUEST["id"];
	$db->execute($sql);	
	
	$vote_like=0;
	$vote_dislike=0;
	$sql="select vote_like,vote_dislike from ".$ptable." where id_parent=".(int)$_REQUEST["id"];
	$dr->open($sql);
	if(!$dr->eof)
	{
		$vote_like=$dr->row["vote_like"];
		$vote_dislike=$dr->row["vote_dislike"];
	}
	
	if($_REQUEST["vote"]>0)
	{
		$rating_text.=$vote_like;
	}
	else
	{
		$rating_text.=$vote_dislike;
	}
}




echo($rating_text);
$db->close();
?>