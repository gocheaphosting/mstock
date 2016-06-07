<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_commission");
?>

<?include("../../members/JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);


$id=(int)@$_REQUEST['id'];
$sql="select id,status from commission where id=".$id;
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["status"]==1)
	{
		$sql="update commission set status=0 where id=".$id;
		$db->execute($sql);
		?>
			<a href="javascript:status(<?=$rs->row["id"]?>);" class="red"><?=word_lang("pending")?></a>
		<?
	}
	else
	{
		$sql="update commission set status=1 where id=".$id;
		$db->execute($sql);
		?>
			<a href="javascript:status(<?=$rs->row["id"]?>);"><?=word_lang("approved")?></a>
		<?
	}
}

$db->close();
?>