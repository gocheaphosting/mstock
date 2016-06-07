<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);


$friend=result3($_REQUEST["friend"]);


if(isset($_SESSION["people_login"]))
{
$sql="delete from friends where friend1='".result($_SESSION["people_login"])."' and friend2='".$friend."'";
$db->execute($sql);
}

$db->close();

?>
<a href="javascript:add_friend('<?=$friend?>')"><?=word_lang("add to friends")?></a>