<?
include("../admin/function/db.php");
header('Content-Type: text/xml');
$str="";
$n=0;

$sql="select title,description from category where id_parent=".(int)$_GET["id"];
$ds->open($sql);
if(!$ds->eof)
{
	$sql="select a.id,a.id_parent,b.id_parent,b.title,b.data,b.description,b.published,b.category2,b.category3 from structure a,photos b where a.id=b.id_parent and (a.id_parent=".(int)$_GET["id"]." or b.category2=".(int)$_GET["id"]." or b.category3=".(int)$_GET["id"].") and b.published=1 order by b.data desc limit 10";
	$rs->open($sql);
	while(!$rs->eof)
	{
		if($n<10)
		{
			$content=$rs->row["description"];
			if(strlen($content)>0)
			{
				$cont=explode(".",$content);
				$content=$cont[0].".";
			}
			$str.="<item><title>" .strip_tags($rs->row["title"]). "</title><description>" .strip_tags($content). "</description><link>".surl.item_url($rs->row["id"])."</link><pubDate>".date("D, d M Y H:i:s",$rs->row["data"])."</pubDate></item>";
		}
		$n++;
		$rs->movenext();
	}


	$sql="select a.id,a.id_parent,b.id_parent,b.title,b.data,b.description,b.published,b.category2,b.category3 from structure a,videos b where a.id=b.id_parent and (a.id_parent=".(int)$_GET["id"]." or b.category2=".(int)$_GET["id"]." or b.category3=".(int)$_GET["id"].") and b.published=1 order by b.data desc limit 10";
	$rs->open($sql);
	while(!$rs->eof)
	{
		if($n<10)
		{
			$content=$rs->row["description"];
			if(strlen($content)>0)
			{
				$cont=explode(".",$content);
				$content=$cont[0].".";
			}
			$str.="<item><title>" .strip_tags($rs->row["title"]). "</title><description>" .strip_tags($content). "</description><link>".surl.item_url($rs->row["id"])."</link><pubDate>".date("D, d M Y H:i:s",$rs->row["data"])."</pubDate></item>";
		}
		$n++;
		$rs->movenext();
	}



	$sql="select a.id,a.id_parent,b.id_parent,b.title,b.data,b.description,b.published,b.category2,b.category3 from structure a,audio b where a.id=b.id_parent and (a.id_parent=".(int)$_GET["id"]." or b.category2=".(int)$_GET["id"]." or b.category3=".(int)$_GET["id"].") and b.published=1 order by b.data desc limit 10";
	$rs->open($sql);
	while(!$rs->eof)
	{
		if($n<10)
		{
			$content=$rs->row["description"];
			if(strlen($content)>0)
			{
				$cont=explode(".",$content);
				$content=$cont[0].".";
			}
			$str.="<item><title>" .strip_tags($rs->row["title"]). "</title><description>" .strip_tags($content). "</description><link>".surl.item_url($rs->row["id"])."</link><pubDate>".date("D, d M Y H:i:s",$rs->row["data"])."</pubDate></item>";
		}
		$n++;
		$rs->movenext();
	}



	$sql="select a.id,a.id_parent,b.id_parent,b.title,b.data,b.description,b.published,b.category2,b.category3 from structure a,vector b where a.id=b.id_parent and (a.id_parent=".(int)$_GET["id"]." or b.category2=".(int)$_GET["id"]." or b.category3=".(int)$_GET["id"].") and b.published=1 order by b.data desc limit 10";
	$rs->open($sql);
	while(!$rs->eof)
	{
		if($n<10)
		{
			$content=$rs->row["description"];
			if(strlen($content)>0)
			{
				$cont=explode(".",$content);
				$content=$cont[0].".";
			}
			$str.="<item><title>" .strip_tags($rs->row["title"]). "</title><description>" .strip_tags($content). "</description><link>".surl.item_url($rs->row["id"])."</link><pubDate>".date("D, d M Y H:i:s",$rs->row["data"])."</pubDate></item>";
		}
		$n++;
		$rs->movenext();
	}


	$str = 	"<?xml version=\"1.0\" encoding=\"utf-8\"?><rss version=\"2.0\"><channel><title>".$global_settings["site_name"]." - Category - ".$ds->row["title"]."</title><link>".surl.category_url((int)$_GET["id"])."</link><description>".$ds->row["description"]."</description><language>en</language>".$str."</channel></rss>";

}

ob_clean();  
echo($str);

$db->close();
?>