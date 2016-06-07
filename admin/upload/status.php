<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_upload");
?>

<?include("../../members/JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);


$id=(int)@$_REQUEST['fid'];
$doc=@$_REQUEST['ftable'];
$fdo=(int)@$_REQUEST['fdo'];

$sql="select id_parent,published from ".result($doc)." where id_parent=".$id;
$rs->open($sql);
if(!$rs->eof)
{
$sql="update ".result($doc)." set published=".$fdo." where id_parent=".$id;
$db->execute($sql);

?>
<a href="javascript:fstatus(<?=$rs->row["id_parent"]?>,1,'<?=$doc?>');" <?if($fdo!=1){?>class="gray"<?}?>><?=word_lang("approved")?></a><br>
<a href="javascript:fstatus(<?=$rs->row["id_parent"]?>,0,'<?=$doc?>');" <?if($fdo!=0){?>class="gray"<?}?>><?=word_lang("pending")?></a><br>
<a href="javascript:fstatus(<?=$rs->row["id_parent"]?>,-1,'<?=$doc?>');" <?if($fdo!=2){?>class="gray"<?}?>><?=word_lang("declined")?></a>
<?
}
$db->close();
?>