<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

$id=(int)$_REQUEST["id"];

$sql="select title,user from blog_categories where id_parent=".$id." and user='".result($_SESSION["people_login"])."'";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<div><input class='ibox form-control' type="text" name="category<?=$id?>" id="category<?=$id?>" style="width:300px" value="<?=$rs->row["title"]?>">&nbsp;<input class='isubmit_orange' type="button" value="<?=word_lang("change")?>" onClick="change(<?=$id?>);" style="margin-top:5px"></div>
	<?
}

$db->close();
?>