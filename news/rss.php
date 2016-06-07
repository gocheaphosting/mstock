<?
include("../admin/function/db.php");
header('Content-Type: text/xml');

$sql="select * from news order by data desc limit 10";
$rs->open($sql);
$str="";
while(!$rs->eof)
{
	$content=translate_text($rs->row["content"]);
	if(strlen($content)>0)
	{
		$cont=explode(".",$content);
		$content=$cont[0].".";
		}

		$str.="<item><title>" .strip_tags($rs->row["announce"]). "</title><description>" .strip_tags($content). "</description><link>".surl.site_root."/news/".$rs->row["id_parent"]."/</link><pubDate>".date("D, d M Y H:i:s",$rs->row["data"])."</pubDate></item>";

	$rs->movenext();
}

$str = 	"<?xml version=\"1.0\" encoding=\"utf-8\"?><rss version=\"2.0\"><channel><title>".$global_settings["site_name"]."</title><link>".surl.site_root."</link><description>News</description><language>en</language>".$str."</channel></rss>";

ob_clean();  
echo($str);

$db->close();
?>