<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_upload");
?>

<?include("../../members/JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);


$id=(int)@$_REQUEST['fid'];
$fdo=(int)@$_REQUEST['fdo'];

$sql="select id_parent,published from category where id_parent=".$id;
$rs->open($sql);
if(!$rs->eof)
{
$sql="update category set published=".$fdo." where id_parent=".$id;
$db->execute($sql);

?>
<a href="javascript:cstatus(<?=$rs->row["id_parent"]?>,1);" <?if($fdo!=1){?>class="gray"<?}?>><?=word_lang("approved")?></a><br>
<a href="javascript:cstatus(<?=$rs->row["id_parent"]?>,0);" <?if($fdo!=0){?>class="gray"<?}?>><?=word_lang("pending")?></a><br>
<a href="javascript:cstatus(<?=$rs->row["id_parent"]?>,2);" <?if($fdo!=2){?>class="gray"<?}?>><?=word_lang("declined")?></a>
<?
}
$db->close();
?>