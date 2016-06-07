<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_customers");
?>

<?include("../../members/JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);


$id=(int)@$_REQUEST['id'];

$sql="select id_parent,accessdenied from users where id_parent=".$id;
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["accessdenied"]!=1)
	{
		$sql="update users set accessdenied=1 where id_parent=".$id;
		$db->execute($sql);
		?>
		<a href="javascript:status_user(<?=$rs->row["id_parent"]?>);"><span class="label label-danger"><?=word_lang("access denied")?></span></a>
		<?
	}
	else
	{
		$sql="update users set accessdenied=0 where id_parent=".$id;
		$db->execute($sql);
		?>
		<a href="javascript:status_user(<?=$rs->row["id_parent"]?>);"><span class="label label-success"><?=word_lang("active")?></span></a>
		<?
	}
}

$db->close();
?>
