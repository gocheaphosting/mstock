<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_orders");
?>

<?include("../../members/JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

$id=(int)@$_REQUEST['id'];
$doc=@$_REQUEST['doc'];

$sql="select * from orders where id=".$id;
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["status"]==1)
	{
		$sql="update orders set status=0 where id=".$id;
		$db->execute($sql);
		?>
			<a href="javascript:doLoad(<?=$rs->row["id"]?>);"><span class="label label-danger"><?=word_lang("pending")?></span></a>
		<?	
		$sql="delete from commission where orderid=".$rs->row["id"]." and total>=0";
		$db->execute($sql);
		
		affiliate_delete_commission($id,"orders");
	}
	else
	{
		order_approve($id);
		send_notification('neworder_to_user',$id);
		coupons_add(order_user($id));
		commission_add($id);
		?>
			<a href="javascript:doLoad(<?=$rs->row["id"]?>);"><span class="label label-success"><?=word_lang("approved")?></span></a>
		<?
	}
}

$db->close();
?>
