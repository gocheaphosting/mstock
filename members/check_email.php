<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);

$email=result($_REQUEST['email']);

if($_REQUEST['email']=="" or !preg_match("/[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?[\.A-Za-z0-9]{2,}/",$_REQUEST['email']))
{
	echo("<span class='error'>".word_lang("incorrect")."</span>");
}
else
{
	$sql="select email from users where email='".$email."'";
	$rs->open($sql);
	if(!$rs->eof)
	{
		echo("<span class='error'>".word_lang("serror2")."</span>");
	}
	else
	{
		echo("<span class='ok'>".word_lang("ok2")."</span>");
	}
}

$db->close();
?>