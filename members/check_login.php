<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

$login=result3($_REQUEST['login']);

if($_REQUEST['login']=="" or !preg_match("/^[A-Za-z]{1,}[A-Za-z0-9]{4,}$/",$_REQUEST['login']))
{
	echo("<span class='error'>".word_lang("incorrect")."</span>");
}
else
{
	$sql="select login from users where login='".$login."'";
	$rs->open($sql);
	if(!$rs->eof)
	{
		echo("<span class='error'>".word_lang("serror1")."</span>");
	}
	else
	{
		echo("<span class='ok'>".word_lang("ok1")."</span>");
	}
}

$db->close();
?>