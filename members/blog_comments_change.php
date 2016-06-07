<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

$id=(int)$_REQUEST["id"];

$sql="update blog_comments set content='".result($_REQUEST["content"])."' where user='".result($_SESSION["people_login"])."' and id_parent=".$id;
$db->execute($sql);


$sql="select id_parent,user,content from blog_comments where user='".result($_SESSION["people_login"])."' and id_parent=".$id;
$rs->open($sql);
if(!$rs->eof)
{
	echo($rs->row["content"]);
}

$db->close();
?>