<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_subscription");
?>

<?include("../../members/JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);


$id=(int)@$_REQUEST['id'];
$sql="select id_parent,approved from subscription_list where id_parent=".$id;
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["approved"]==1)
	{
		$sql="update subscription_list set approved=0 where id_parent=".$id;
		$db->execute($sql);
		?>
			<a href="javascript:doLoad(<?=$id?>);"><span class="label label-danger"><?=word_lang("pending")?></span></a>
		<?
		affiliate_delete_commission($id,"subscription");
	}
	else
	{
		subscription_approve($id);
		send_notification('subscription_to_user',$id);
		send_notification('subscription_to_admin',$id);
		?>
			<a href="javascript:doLoad(<?=$id?>);"><span class="label label-success"><?=word_lang("approved")?></span></a>
		<?
	}
}

$db->close();
?>
