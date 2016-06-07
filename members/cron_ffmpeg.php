<?
include("../admin/function/db.php");
include("../admin/function/upload.php");

$sql="select a.id,a.generation,a.data2,a.data1,b.id_parent,b.server1,b.userid from ffmpeg_cron a,videos b where a.data2=0 and b.id_parent=a.id order by a.data1 limit 0,5";
$rs->open($sql);
while(!$rs->eof)
{
	$server1=$rs->row["server1"];
	$userid=$rs->row["userid"];
	
	$url="";
	$url2="";
	$sql="select price_id,url from items where id_parent=".$rs->row["id"]." and shipped=0";
	$ds->open($sql);
	while(!$ds->eof)
	{
		if($ds->row["url"]!="")
		{
			$url2=$ds->row["url"];
			
			if($ds->row["price_id"]==$rs->row["generation"])
			{
				$url=$ds->row["url"];
			}
		}
		$ds->movenext();
	}
	
	if($url=="")
	{
		$url=$url2;
	}
	
	if($url!="" and file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server1]."/".$rs->row["id"]."/".$url))
	{
		generate_flv($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$server1]."/".$rs->row["id"]."/".$url,0,0);
		
		$sql="update ffmpeg_cron set data2=".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." where id=".$rs->row["id"];
		$db->execute($sql);
		
		if($userid==0 or $global_settings["moderation"]==0)
		{
			$sql="update videos set published=1 where id_parent=".$rs->row["id"];
			$db->execute($sql);
		}
		
		echo("<p>Previews for video ID#".$rs->row["id"]." have been generated.</p>");
	}
	
	$rs->movenext();
}


$db->close();
?>
