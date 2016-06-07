<?php
include("../admin/function/db.php");
include("../admin/function/upload.php");

//Generate preview for the photos

$sql="select id_parent,server1,url_jpg from photos where id_parent>" . $global_settings["cron_photos"] . " order by id_parent limit 0,5";
$rs->open($sql);

$photo_id= $global_settings["cron_photos"];
while(!$rs->eof)
{
	$sql="select url from filestorage_files where id_parent=".$rs->row["id_parent"];
	$ds->open($sql);
	if($ds->eof)
	{
		if(!file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$rs->row["id_parent"]."/thumb1.jpg") and file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$rs->row["id_parent"]."/".$rs->row["url_jpg"]))
		{			
			photo_resize($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$rs->row["id_parent"]."/".$rs->row["url_jpg"],$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$rs->row["id_parent"]."/thumb2.jpg",2);
			
			photo_resize($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$rs->row["id_parent"]."/thumb2.jpg",$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$rs->row["id_parent"]."/thumb1.jpg",1);
				
			publication_watermark_add($rs->row["id_parent"],$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$rs->row["server1"]]."/".$rs->row["id_parent"]."/thumb2.jpg");
			
			echo("<p><img src='".site_root.$site_servers[$rs->row["server1"]]."/".$rs->row["id_parent"]."/thumb1.jpg'><br><small>Photo ID: ".$rs->row["id_parent"]."</small></p>");
		}
	}
	
	$photo_id = $rs->row["id_parent"];
	
	$rs->movenext();
}

$sql="update settings set svalue=".$photo_id." where setting_key='cron_photos'";
$db->execute($sql);

$db->close();
?>