<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);
$db->close();
?><img src='<?=site_root?>/members/image.php?id=<?=$_REQUEST["id"]?>&x1=<?=$_REQUEST["x1"]?>&x0=<?=$_REQUEST["x0"]?>&y1=<?=$_REQUEST["y1"]?>&y0=<?=$_REQUEST["y0"]?>&z=<?=$_REQUEST["z"]?>&width=<?=$_REQUEST["width"]?>&height=<?=$_REQUEST["height"]?>'>