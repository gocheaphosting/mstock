<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_support");

include("../../members/JsHttpRequest.php");

$JsHttpRequest =new JsHttpRequest($mtg);

$id=(int)@$_REQUEST['id'];

$sql="select id,closed from support_tickets where id=".$id;
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["closed"]==1)
	{
		$sql="update support_tickets set closed=0 where id=".$id;
		$db->execute($sql);
		?>
			<a href="javascript:doLoad(<?=$rs->row["id"]?>);" class="red"><?=word_lang("in progress")?></a>
		<?	
	}
	else
	{
		$sql="update support_tickets set closed=1 where id=".$id;
		$db->execute($sql);
		?>
			<a href="javascript:doLoad(<?=$rs->row["id"]?>);"><?=word_lang("closed")?></a>
		<?
	}
}

$db->close();
?>
