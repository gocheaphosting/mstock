<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_bulkupload");

include("../function/upload.php");

$swait=false;



$afiles=array();

  $dir = opendir ($_SERVER["DOCUMENT_ROOT"].site_root.$global_settings["photopreupload"]);
  while ($file = readdir ($dir)) 
  {
    if($file <> "." && $file <> "..")
    {
		if(preg_match("/.jpg$|.jpeg$/i",$file)) 
		{ 
			$afiles[count($afiles)]=$file;
		}
    }
  }
closedir ($dir);
sort ($afiles);
reset ($afiles);

$photo_formats=array();
$sql="select id,photo_type from photos_formats where enabled=1 and photo_type<>'jpg' order by id";
$dr->open($sql);
while(!$dr->eof)
{
	$photo_formats[$dr->row["id"]]=$dr->row["photo_type"];
	$dr->movenext();
}



for($j=0;$j<count($afiles);$j++)
{
	if(isset($_POST["f".$j]))
	{
		$photo="";

		if($_POST["file".$j]!="")
		{
			$title=result($_POST["title".$j]);
			if($title=="")
			{
				$ttl=explode(".",$_POST["file".$j]);
				$title=str_replace("_","",$ttl[0]);
			}

			$pub_vars=array();
			$pub_vars["category"]=(int)$_POST["category"];
			$pub_vars["title"]=$title;
			$pub_vars["description"]=result($_POST["description".$j]);
			$pub_vars["keywords"]=result($_POST["keywords".$j]);
			//$pub_vars["userid"]=user_url($_POST["author"]);
			$pub_vars["userid"]=0;
			$pub_vars["published"]=1;
			$pub_vars["viewed"]=0;
			$pub_vars["data"]=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
			$pub_vars["author"]=result($_POST["author"]);
			$pub_vars["content_type"]=$global_settings["content_type"];
			$pub_vars["downloaded"]=0;
			$pub_vars["model"]=0;
			$pub_vars["examination"]=0;
			$pub_vars["server1"]=$site_server_activ;
			$pub_vars["free"]=0;
			$pub_vars["category2"]=(int)$_POST["category2"];
			$pub_vars["category3"]=(int)$_POST["category3"];

			$pub_vars["google_x"]=0;
			$pub_vars["google_y"]=0;
			$pub_vars["editorial"]=0;
			$pub_vars["adult"]=0;

			//Add a new photo to the database
			$id=publication_photo_add();

			$folder=$id;

			$photo=site_root.$global_settings["photopreupload"].result($_POST["file".$j]);

			//create thumbs and watermark
			if($photo!="" and preg_match("/.jpg$|.jpeg$/i",$photo) and !file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb1.jpg")) 
			{ 
				photo_resize($_SERVER["DOCUMENT_ROOT"].$photo,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb1.jpg",1);

				photo_resize($_SERVER["DOCUMENT_ROOT"].$photo,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb2.jpg",2);
				
				publication_watermark_add($id,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb2.jpg");
			}
			
			//Other formats
			$filename=get_file_info($_POST["file".$j],"filename");
			foreach ($photo_formats as $key => $value) 
			{
				$filecopy="";
				
				if($value=="tiff")
				{
					if(file_exists($DOCUMENT_ROOT.$global_settings["photopreupload"]."/".$filename.".tif"))
					{
						copy($DOCUMENT_ROOT.$global_settings["photopreupload"]."/".$filename.".tif",$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".$filename.".tif");
						$filecopy=$filename.".tif";
					}
					if(file_exists($DOCUMENT_ROOT.$global_settings["photopreupload"]."/".$filename.".tiff"))
					{
						copy($DOCUMENT_ROOT.$global_settings["photopreupload"]."/".$filename.".tiff",$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".$filename.".tiff");
						$filecopy=$filename.".tiff";
					}
				}
				else
				{
					if(file_exists($DOCUMENT_ROOT.$global_settings["photopreupload"]."/".$filename.".".$value))
					{
						copy($DOCUMENT_ROOT.$global_settings["photopreupload"]."/".$filename.".".$value,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".$filename.".".$value);
						$filecopy=$filename.".".$value;
					}
				}
				
				if($filecopy!="")
				{
					$sql="update photos set url_".$value."='".result($filecopy)."' where id_parent=".$id;
					$db->execute($sql);
				}
				
				if(isset($_POST["remove"]) and $filecopy!="")
				{
					@unlink($DOCUMENT_ROOT.$global_settings["photopreupload"]."/".$filecopy);
				}
			}

			//create different dimensions
			if($photo!="")
			{
				copy($_SERVER["DOCUMENT_ROOT"].$photo,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".result($_POST["file".$j]));
				$file=$_POST["file".$j];
				
				$sql="update photos set url_jpg='".result($_POST["file".$j])."' where id_parent=".$id;
				$db->execute($sql);
								
				//Rights managed
				if(isset($_POST["license_type"]) and (int)$_POST["license_type"]==1)
				{
					if(isset($_POST["rights_id"]))
					{
						$sql="update photos set rights_managed=".(int)@$_POST["rights_id"]." where id_parent=".$id;
						$db->execute($sql);
					
						//Create photo sizes
						publication_photo_sizes_add($id,$file,false,"rights_managed",(int)@$_POST["rights_id"]);
					}	
				}
				else
				{
					//Create photo sizes
					publication_photo_sizes_add($id,$file,false);
				}
	
				//Google coordinates
				$exif_info=@exif_read_data($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".result($_POST["file".$j]),0,true);
				if(isset($exif_info["GPS"]["GPSLongitude"]) and isset($exif_info["GPS"]['GPSLongitudeRef']) and isset($exif_info["GPS"]["GPSLatitude"]) and isset($exif_info["GPS"]['GPSLatitudeRef']))
				{
					$lon = getGps($exif_info["GPS"]["GPSLongitude"], $exif_info["GPS"]['GPSLongitudeRef']);
					$lat = getGps($exif_info["GPS"]["GPSLatitude"], $exif_info["GPS"]['GPSLatitudeRef']);
		
					$sql="update photos set google_x=".$lat.",google_y=".$lon." where id_parent=".$id;
					$db->execute($sql);
				}
			}

			//Prints
			if($global_settings["prints"])
			{
				publication_prints_add($id,false);
			}
			
			if(isset($_POST["remove"]) and $photo!="")
			{
				@unlink($_SERVER["DOCUMENT_ROOT"].$photo);
			}
		}


	}
}

$db->close();

//go back
redirect_file("../catalog/index.php?category_id=".(int)$_POST["category"],true);
?>