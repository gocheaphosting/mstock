<?
include("../admin/function/db.php");
header('Content-Type: text/xml');

$sql="select id_parent,title,content,data from blog where user='".result3(user_url_back($_GET["user"]))."' order by data desc limit 10";
$rs->open($sql);
$str="";
while(!$rs->eof)
{
	$content=$rs->row["content"];
	
	if(strlen($content)>0)
	{
		$cont=explode(".",$content);
		$content=$cont[0].".";
	}

	$str.="<item><title>" .strip_tags($rs->row["title"]). "</title><description>" .strip_tags($content). "</description><link>".surl.site_root."/post/".user_url($_GET["user"])."/".$rs->row["id_parent"].".html</link><pubDate>".date("D, d M Y H:i:s",$rs->row["data"])."</pubDate></item>";
	
	$rs->movenext();
}

$str = 	"<?xml version=\"1.0\" encoding=\"utf-8\"?><rss version=\"2.0\"><channel><title>".$global_settings["site_name"]." - Blog - ".user_url_back($_GET["user"])."</title><link>".surl.site_root."</link><description>Posts</description><language>en</language>".$str."</channel></rss>";

ob_clean();  
echo($str);

$db->close();
?>