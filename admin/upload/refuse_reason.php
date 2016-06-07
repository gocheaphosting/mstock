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
$content=@$_REQUEST['fcontent'];


$sql="select id_parent,published from ".result($doc)." where id_parent=".$id;
$rs->open($sql);
if(!$rs->eof)
{
$sql="update ".result($doc)." set refuse_reason='".result($content)."' where id_parent=".$id;
$db->execute($sql);

echo($content);
}
?>