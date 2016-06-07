<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_orders");
?>

<?include("../../members/JsHttpRequest.php");?>
<?
$JsHttpRequest =& new JsHttpRequest($mtg);


$id=(int)@$_REQUEST['id'];


$sql="select * from orders where id=".$id;
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["shipped"]==1)
	{
		$sql="update orders set shipped=0 where id=".$id;
		$db->execute($sql);
		?>
			<a href="javascript:doLoad4(<?=$rs->row["id"]?>);"><span class="label label-warning"><?=word_lang("not shipped")?></span></a>
		<?
	}
	else
	{
		$sql="update orders set shipped=1 where id=".$id;
		$db->execute($sql);
		?>
			<a href="javascript:doLoad4(<?=$rs->row["id"]?>);"><span class="label label-info"><?=word_lang("shipped")?></span></a>
		<?
	}
}

$db->close();
?>