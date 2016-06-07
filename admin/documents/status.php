<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_documents");

include("../../members/JsHttpRequest.php");

$JsHttpRequest =new JsHttpRequest($mtg);


$id=(int)@$_REQUEST['fid'];
$fdo=(int)@$_REQUEST['fdo'];

$sql="select id,status from documents where id=".$id;
$rs->open($sql);
if(!$rs->eof)
{
	$sql="update documents set status=".$fdo." where id=".$id;
	$db->execute($sql);

	?>
	<a href="javascript:fstatus(<?=$rs->row["id"]?>,1);" <?if($fdo!=1){?>class="gray"<?}?>><?=word_lang("approved")?></a><br>
	<a href="javascript:fstatus(<?=$rs->row["id"]?>,0);" <?if($fdo!=0){?>class="gray"<?}?>><?=word_lang("pending")?></a><br>
	<a href="javascript:fstatus(<?=$rs->row["id"]?>,-1);" <?if($fdo!=-1){?>class="gray"<?}?>><?=word_lang("declined")?></a>
	<?
}
$db->close();
?>