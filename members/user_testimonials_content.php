<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);


$login=result3($_REQUEST['login']);

	if(isset($_SESSION["people_login"]))
	{
		if(isset($_REQUEST['content']))
		{
			$content=@$_REQUEST['content'];
			$login=@$_REQUEST['login'];

			$sql="insert into testimonials (touser,fromuser,content,data) values ('".result3($login)."','".result($_SESSION["people_login"])."','".result($content)."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).")";
			$db->execute($sql);
		}
	}


$sql="select id_parent,touser,fromuser,content,data from testimonials where touser='".$login."' order by data desc";
$rs->open($sql);
$boxtestimonials="";
if(!$rs->eof)
{
	$boxtestimonials.="<table border='0' cellpadding='0' cellspacing='0' style='margin-bottom:20px'>";
	while(!$rs->eof)
	{
		$boxuser=show_user_avatar($rs->row["fromuser"],"login");
		
		$boxtestimonials.="<tr valign='top'><td rowspan='2' style='padding-right:20px'>".$boxuser."</td><td class='datenews'>".show_time_ago($rs->row["data"])."</td></tr><tr><td style='padding-bottom:15px'>".str_replace("\n","<br>",$rs->row["content"])."</td></tr>";
		$rs->movenext();
	}
	$boxtestimonials.="</table>";
}
else
{
	$boxtestimonials.="<p><b>".word_lang("not found")."</b></p>";
}

$boxadd="";
if(isset($_SESSION["people_id"]))
{
	$boxadd=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."testimonials_add.tpl");
}
else
{
	$boxadd="<a href='{SITE_ROOT}members/login.php'>".word_lang("add review")."</a>";
}


$boxcontent="";
if(isset($_SESSION["people_login"])){$boxcontent.=$boxadd;}

$boxcontent.=$boxtestimonials;
$boxcontent=str_replace("{WORD_NEW}",word_lang("new testimonial"),$boxcontent);
$boxcontent=str_replace("{WORD_CONTENT}",word_lang("content"),$boxcontent);
$boxcontent=str_replace("{WORD_ADD}",word_lang("add"),$boxcontent);

$boxcontent=str_replace("{LOGIN}",$login,$boxcontent);

$boxcontent=str_replace("{SITE_ROOT}",site_root."/",$boxcontent);


echo($boxcontent);

?>
