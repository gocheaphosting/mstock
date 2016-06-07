<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_bulkupload");

include("../function/upload.php");

$swait=false;

$ids=array();
//Rights managed
$rights_managed=0;

if(isset($_POST["license_type"]) and (int)$_POST["license_type"]==1)
{
	$sql="select id from rights_managed where video=1";
	$ds->open($sql);
	while(!$ds->eof)
	{
		$ids[]=$ds->row["id"];
		$ds->movenext();
	}
	$rights_managed=1;
}
else
{
	$sql="select id_parent from video_types order by priority";
	$ds->open($sql);
	while(!$ds->eof)
	{
		$ids[]=$ds->row["id_parent"];
		$ds->movenext();
	}
}

for($j=0;$j<$global_settings["bulk_upload"];$j++)
{
	$flag_upload=false;
	for($i=0;$i<count($ids);$i++)
	{
		if(isset($_POST["file".$ids[$i]."_".$j]) and $_POST["file".$ids[$i]."_".$j]!=""){$flag_upload=true;}
	}

	if($flag_upload==true)
	{
		$video="";

		$title=result($_POST["title".$j]);
		if($title=="")
		{
			$title="video".$j;
		}

		$usa="";
		$format="";
		$ratio="";
		$rendering="";
		$frames="";
		$holder="";
		if(isset($_POST["usa".$j])){$usa=result($_POST["usa".$j]);}
		if(isset($_POST["format".$j])){$format=result($_POST["format".$j]);}
		if(isset($_POST["ratio".$j])){$ratio=result($_POST["ratio".$j]);}
		if(isset($_POST["rendering".$j])){$rendering=result($_POST["rendering".$j]);}
		if(isset($_POST["frames".$j])){$frames=result($_POST["frames".$j]);}
		if(isset($_POST["holder".$j])){$holder=result($_POST["holder".$j]);}

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
		$pub_vars["model"]=(int)$_POST["model".$j];
		$pub_vars["examination"]=0;
		$pub_vars["server1"]=$site_server_activ;
		$pub_vars["free"]=0;
		$pub_vars["category2"]=(int)$_POST["category2"];
		$pub_vars["category3"]=(int)$_POST["category3"];

		$pub_vars["duration"]=3600*$_POST["duration".$j."_hour"]+60*$_POST["duration".$j."_minute"]+(int)$_POST["duration".$j."_second"];
		$pub_vars["format"]=$format;
		$pub_vars["ratio"]=$ratio;
		$pub_vars["rendering"]=$rendering;
		$pub_vars["frames"]=$frames;
		$pub_vars["holder"]=$holder;
		$pub_vars["usa"]=$usa;

		$pub_vars["google_x"]=0;
		$pub_vars["google_y"]=0;
		$pub_vars["adult"]=0;

		//Add a new video to the database
		$id=publication_video_add();

		$folder=$id;

		$previewvideo=site_root.$global_settings["videopreupload"].@$_POST["previewvideo".$j];
		$previewphoto=site_root.$global_settings["videopreupload"].@$_POST["previewphoto".$j];

		//copy file
		if($rights_managed==0)
		{
			$sql="select * from video_types order by priority";
			$ds->open($sql);
			while(!$ds->eof)
			{
				if($ds->row["shipped"]!=1)
				{
					if(isset($_POST["file".$ds->row["id_parent"]."_".$j]) and $_POST["file".$ds->row["id_parent"]."_".$j]!="")
					{
						$video=site_root.$global_settings["videopreupload"].result($_POST["file".$ds->row["id_parent"]."_".$j]);
						copy($_SERVER["DOCUMENT_ROOT"].$video,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".result($_POST["file".$ds->row["id_parent"]."_".$j]));
				
						$file=$_POST["file".$ds->row["id_parent"]."_".$j];
	
						$sql="insert into items (id_parent,name,url,price,priority,shipped,price_id) values (".$id.",'".$ds->row["title"]."','".result($file)."',".floatval($ds->row["price"]).",".$ds->row["priority"].",0,".$ds->row["id_parent"].")";
						$db->execute($sql);
					}
				}
				else
				{
					if(isset($_POST["file".$ds->row["id_parent"]."_".$j]))
					{
						$sql="insert into items (id_parent,name,url,price,priority,shipped,price_id) values (".$id.",'".$ds->row["title"]."','".result($file)."',".floatval($ds->row["price"]).",".$ds->row["priority"].",1,".$ds->row["id_parent"].")";
						$db->execute($sql);
					}
				}
							
				$ds->movenext();
			}
		}
		else
		{
			$flag_rights=false;
			
			$sql="select id,price,title from rights_managed where video=1";
			$ds->open($sql);
			while(!$ds->eof)
			{
				if(isset($_POST["file".$ds->row["id"]."_".$j]) and $_POST["file".$ds->row["id"]."_".$j]!="" and $flag_rights==false)
				{
					$video=site_root.$global_settings["videopreupload"].result($_POST["file".$ds->row["id"]."_".$j]);
					copy($_SERVER["DOCUMENT_ROOT"].$video,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".result($_POST["file".$ds->row["id"]."_".$j]));
				
					$file=$_POST["file".$ds->row["id"]."_".$j];
	
					$sql="insert into items (id_parent,name,url,price,priority,shipped,price_id) values (".$id.",'".$ds->row["title"]."','".result($file)."',".floatval($ds->row["price"]).",0,0,".$ds->row["id"].")";
					$db->execute($sql);
					
					$sql="update videos set rights_managed=".(int)$ds->row["id"]." where id_parent=".$id;
					$db->execute($sql);
					
					if($global_settings["ffmpeg"])
					{
						$generation_file=$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".result($_POST["file".$ds->row["id"]."_".$j]);		
						$fln=generate_flv($generation_file,0,0);
					}
					
					$flag_rights=true;
				}
							
				$ds->movenext();
			}		
		}

		if(!$global_settings["ffmpeg"])
		{
			//Video preview
			$fn=explode(".",strtolower($_POST["previewvideo".$j]));
			if($_POST["previewvideo".$j]!="" and ($fn[count($fn)-1]=="flv" or $fn[count($fn)-1]=="wmv" or $fn[count($fn)-1]=="mp4" or $fn[count($fn)-1]=="mov"))
			{
				$vp=site_root.$site_servers[$site_server_activ]."/".$folder."/thumb.".$fn[count($fn)-1];
				copy($_SERVER["DOCUMENT_ROOT"].$previewvideo,$_SERVER["DOCUMENT_ROOT"].$vp);
			}

			//Photo preview
			$fn=explode(".",strtolower($_POST["previewphoto".$j]));
			if($_POST["previewphoto".$j]!="" and ($fn[count($fn)-1]=="jpg" or $fn[count($fn)-1]=="jpeg"))
			{
				$vp=site_root.$site_servers[$site_server_activ]."/".$folder."/thumb.".$fn[count($fn)-1];
				$vp_big=site_root.$site_servers[$site_server_activ]."/".$folder."/thumb100.".$fn[count($fn)-1];

				photo_resize($_SERVER["DOCUMENT_ROOT"].$previewphoto,$_SERVER["DOCUMENT_ROOT"].$vp,1);
				photo_resize($_SERVER["DOCUMENT_ROOT"].$previewphoto,$_SERVER["DOCUMENT_ROOT"].$vp_big,2);
			}
		}
		else
		{
			if($rights_managed==0 and $global_settings["ffmpeg_cron"])
			{
				$sql="insert into ffmpeg_cron (id,data1,data2,generation) values (".$id.",".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",0,".(int)$_POST["generation".$j].")";
				$db->execute($sql);
		
				$sql="update videos set published=0 where id_parent=".$id;
				$db->execute($sql);
			}
			
			//FFMPEG generation
			if($rights_managed==0 and !$global_settings["ffmpeg_cron"])
			{
				//Define a source file for generation
				$generation_file="";
				$generation_file2="";
				$sql="select * from video_types order by priority";
				$ds->open($sql);
				while(!$ds->eof)
				{
					if($_POST["file".$ds->row["id_parent"]."_".$j]!="")
					{
						if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".result($_POST["file".$ds->row["id_parent"]."_".$j])))
						{
							$generation_file2=$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".result($_POST["file".$ds->row["id_parent"]."_".$j]);

							if((int)$_POST["generation".$j]==$ds->row["id_parent"])
							{
								$generation_file=$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".result($_POST["file".$ds->row["id_parent"]."_".$j]);
							}
						}
					}
				
					$ds->movenext();
				}
	
				if($generation_file=="")
				{
					$generation_file=$generation_file2;
				}

				if($generation_file!="")
				{
					$fln=generate_flv($generation_file,0,0);
				}
			}
		}
	}
}

$db->close();

//go back
redirect_file("../catalog/index.php?category_id=".(int)$_POST["category"],true);
?>