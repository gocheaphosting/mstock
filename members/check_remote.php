<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);





$remote=$_REQUEST['remote'];


if (!remote_check($remote))
{
	echo(word_lang("unable to open remote file"));
}
else
{
	echo(word_lang("file is avaivable"));
}

$db->close();
?>