<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);


$id=(int)$_REQUEST["id"];

$sql="select id_parent,fromuser,content from reviews where fromuser='".result($_SESSION["people_login"])."' and id_parent=".$id;
$rs->open($sql);
if(!$rs->eof)
{
?>
<div><textarea class='ibox form-control' name="content<?=$rs->row["id_parent"]?>" id="content<?=$rs->row["id_parent"]?>" style="width:300px;height:70px"><?=$rs->row["content"]?></textarea></div>
<div><input class='isubmit_orange' type="button" value="<?=word_lang("change")?>" onClick="change(<?=$rs->row["id_parent"]?>);" style="margin-top:5px"></div>
<?
}

$db->close();
?>