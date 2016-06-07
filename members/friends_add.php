<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);


$friend=result3($_REQUEST["friend"]);


if(isset($_SESSION["people_login"]) and $friend!=$_SESSION["people_login"])
{
	$sql="select friend1,friend2 from friends where friend1='".result($_SESSION["people_login"])."' and friend2='".$friend."'";
	$rs->open($sql);
	if($rs->eof)
	{	
		$sql="insert into friends (friend1,friend2) values ('".result($_SESSION["people_login"])."','".$friend."')";
		$db->execute($sql);
	}
}

$db->close();
?>
<a href="javascript:delete_friend('<?=$friend?>')"><?=word_lang("delete from friends")?></a>