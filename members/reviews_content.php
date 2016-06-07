<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);


$id=@$_REQUEST['id'];
if(isset($_REQUEST['content']))
{
	$content=@$_REQUEST['content'];
	$login=@$_REQUEST['login'];


	$sql="insert into reviews (fromuser,content,data,itemid) values ('".result3($login)."','".result($content)."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",".(int)$id.")";
	$db->execute($sql);
}


	$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."reviews.tpl");
	$boxcontent=str_replace("{TEMPLATE_ROOT}",site_root."/".$site_template_url,$boxcontent);
	$boxcontent=str_replace("{WORD_REVIEWS}",word_lang("reviews"),$boxcontent);




$sql="select itemid,content,data,fromuser from reviews where itemid=".(int)$id." order by data desc";
$rs->open($sql);
$boxreviews="";
if(!$rs->eof)
{
	$boxreviews.="<table border='0' cellpadding='0' cellspacing='0' style='margin-bottom:20px'>";
	while(!$rs->eof)
	{
		$boxuser=show_user_avatar($rs->row["fromuser"],"login");

		$boxreviews.="<tr valign='top'><td rowspan='2' style='padding-right:20px'>".$boxuser."</td><td class='datenews'>".show_time_ago($rs->row["data"])."</td></tr><tr><td style='padding-bottom:15px'>".str_replace("\n","<br>",$rs->row["content"])."</td></tr>";
		$rs->movenext();
	}
	$boxreviews.="</table>";
}
else
{
	$boxreviews.="<p>".word_lang("not found")."</p>";
}

$boxadd="";
if(isset($_SESSION["people_id"]))
{
	$boxadd=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."reviews_add.tpl");
}
else
{
	$boxadd="<a href='{SITE_ROOT}members/login.php'>".word_lang("add review")."</a>";
}



$boxcontent=str_replace("{CONTENT}",$boxreviews.$boxadd,$boxcontent);
$boxcontent=str_replace("{WORD_NEW}",word_lang("new review"),$boxcontent);
$boxcontent=str_replace("{WORD_CONTENT}",word_lang("content"),$boxcontent);
$boxcontent=str_replace("{WORD_ADD}",word_lang("add"),$boxcontent);
$boxcontent=str_replace("{ID}",strval($id),$boxcontent);
if(isset($_SESSION["people_login"]))
{
	$boxcontent=str_replace("{LOGIN}",$_SESSION["people_login"],$boxcontent);
}
else
{
	$boxcontent=str_replace("{LOGIN}","",$boxcontent);
}
$rr=rand(0,9);
$boxcontent=str_replace("{RR}",strval($rr),$boxcontent);
$boxcontent=str_replace("{SITE_ROOT}",site_root."/",$boxcontent);

echo($boxcontent);

$db->close();
?>
