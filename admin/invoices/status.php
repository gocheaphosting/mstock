<? 
include("../function/db.php");

//Check access
admin_panel_access("orders_invoices");

include("../../members/JsHttpRequest.php");

$JsHttpRequest =new JsHttpRequest($mtg);

$id=(int)@$_REQUEST['id'];
$sql="select id,status from invoices where id=".$id;
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["status"]==1)
	{
		$sql="update invoices set status=0 where id=".$id;
		$db->execute($sql);
		?>
			<a href="javascript:doLoad(<?=$id?>);"><span class="label label-danger"><?=word_lang("pending")?></span></a>
		<?
	}
	else
	{
		$sql="update invoices set status=1 where id=".$id;
		$db->execute($sql);
		?>
			<a href="javascript:doLoad(<?=$id?>);"><span class="label label-success"><?=word_lang("published")?></span></a>
		<?
	}
}
$db->close();
?>
