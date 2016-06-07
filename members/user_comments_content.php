<?include("../admin/function/db.php");?>
<?include("JsHttpRequest.php");?>
<?
$JsHttpRequest =new JsHttpRequest($mtg);



$postid=(int)$_REQUEST['postid'];


if(isset($_SESSION["people_login"]))
{
	if(isset($_REQUEST['content']))
	{
		$sql="insert into blog_comments (user,content,data,postid) values ('".result($_SESSION["people_login"])."','".result($_REQUEST['content'])."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",".$postid.")";
		$db->execute($sql);
	}
}


$sql="select id_parent,user,content,data,postid from blog_comments where postid=".$postid." order by data";
$rs->open($sql);
$boxcomments="";
if(!$rs->eof)
{
	$boxcomments.="<table border='0' cellpadding='0' cellspacing='0' style='margin-bottom:20px'>";
	while(!$rs->eof)
	{
		$boxuser=show_user_avatar($rs->row["user"],"login");

		$boxcomments.="<tr valign='top'><td rowspan='2' style='padding-right:20px'>".$boxuser."</td><td class='datenews'>".show_time_ago($rs->row["data"])."</td></tr><tr><td style='padding-bottom:15px'>".str_replace("\n","<br>",$rs->row["content"])."</td></tr>";
		$rs->movenext();
	}
	$boxcomments.="</table>";
}
else
{
	$boxcomments.="<p><b>".word_lang("not found")."</b></p>";
}

$boxadd="";
if(isset($_SESSION["people_id"]))
{
	$boxadd=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."comments_add.tpl");
}
else
{
	$boxadd="<a href='{SITE_ROOT}members/login.php'>".word_lang("add comment")."</a>";
}


$boxcontent="";


$boxcontent.=$boxcomments;
$boxcontent.=$boxadd;


$boxcontent=str_replace("{WORD_NEW}",word_lang("new comment"),$boxcontent);
$boxcontent=str_replace("{WORD_CONTENT}",word_lang("content"),$boxcontent);
$boxcontent=str_replace("{WORD_ADD}",word_lang("add"),$boxcontent);

$boxcontent=str_replace("{POSTID}",strval($postid),$boxcontent);

$rr=rand(0,9);
$boxcontent=str_replace("{RR}",strval($rr),$boxcontent);
$boxcontent=str_replace("{SITE_ROOT}",site_root."/",$boxcontent);


echo($boxcontent);

?>
