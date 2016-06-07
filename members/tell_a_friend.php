<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);





$id=@$_REQUEST['id'];


if(isset($_REQUEST['email']))
{
$rn1=@$_REQUEST['rn1'];
$rn2=@$_REQUEST['rn2'];
$boxtell="<p><b>".word_lang("sent")."</b></p>";

$rn=array("d3w5","26wy","g3z9","a4n8","7fq2","5n6s","g6mz","6ct9","v8z2","b43j");
if($rn[(int)$rn2]==strtolower($rn1))
{









$url=surl.item_url((int)$_REQUEST["id"]);

if(preg_match("/@/",$_REQUEST["email"]) and preg_match("/@/",$_REQUEST["email2"]))
{
send_notification('tell_a_friend',$url);
}
else
{
$boxtell="<p><b>".word_lang("error happened")."</b></p>";
}










}
else
{
$boxtell="<p><b>".word_lang("error happened")."</b></p>";
}

}
else
{

$boxtell=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."tell_a_friend_form.tpl");
$boxtell=str_replace("{YOUR NAME}",word_lang("your name"),$boxtell);
$boxtell=str_replace("{YOUR EMAIL}",word_lang("your e-mail"),$boxtell);
$boxtell=str_replace("{FRIEND NAME}",word_lang("friend name"),$boxtell);
$boxtell=str_replace("{FRIEND EMAIL}",word_lang("friend e-mail"),$boxtell);
$boxtell=str_replace("{SEND}",word_lang("send"),$boxtell);
$boxtell=str_replace("{INCORRECT}",word_lang("incorrect"),$boxtell);
$boxtell=str_replace("{PLEASE INSERT}",word_lang("please insert"),$boxtell);
$boxtell=str_replace("{CAPTCHA}",word_lang("captcha"),$boxtell);


}



$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."tell_a_friend.tpl");
$boxcontent=str_replace("{TEMPLATE_ROOT}",site_root."/".$site_template_url,$boxcontent);
$boxcontent=str_replace("{WORD_TELL}",word_lang("tell a friend"),$boxcontent);
$boxcontent=str_replace("{CONTENT}",$boxtell,$boxcontent);
$boxcontent=str_replace("{ID}",strval($id),$boxcontent);
$rr=rand(0,9);
$boxcontent=str_replace("{RR}",strval($rr),$boxcontent);
$boxcontent=str_replace("{SITE_ROOT}",site_root."/",$boxcontent);

echo($boxcontent);

$db->close();
?>
