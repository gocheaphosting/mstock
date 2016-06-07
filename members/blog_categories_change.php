<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

$id=(int)$_REQUEST["id"];

$sql="update blog_categories set title='".result3($_REQUEST["category"])."' where id_parent=".$id." and user='".result($_SESSION["people_login"])."'";
$db->execute($sql);

$sql="select title,user from blog_categories where id_parent=".$id." and user='".result($_SESSION["people_login"])."'";
$rs->open($sql);
if(!$rs->eof)
{
	echo($rs->row["title"]);
}

$db->close();
?>