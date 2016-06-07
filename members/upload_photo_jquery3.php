<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?if($global_settings["userupload"]==0){header("location:profile.php");}?>
<?
$sql="select * from user_category where name='".result($_SESSION["people_category"])."'";
$dn->open($sql);
if(!$dn->eof and $dn->row["upload"]==1)
{
$lphoto=$dn->row["photolimit"];

$swait=false;

//Upload function
include("../admin/function/upload.php");

$tmp_folder="user_".(int)$_SESSION["people_id"];

$afiles=array();

  $dir = opendir ($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder);
  while ($file = readdir ($dir)) 
  {
    if($file <> "." && $file <> "..")
    {
		if (preg_match("/.jpg$|.jpeg$/i",$file) and !preg_match("/thumb/i",$file)) 
		{ 
			$file=result_file($file);
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


//free
$free=0;
if(isset($_POST["free"]))
{
	$free=1;
}



//Examination
if($global_settings["examination"] and $_SESSION["people_exam"]!=1)
{
	$exam=1;
}
else
{
	$exam=0;
}



//Upload set of photos
if(isset($afiles))
{
	for($n=0;$n<count($afiles);$n++)
	{
		$photo="";

		$fileName=preg_replace("/\.jpg$/i","",$afiles[$n]);
		$title=str_replace("_","",$fileName);

		$pub_vars=array();
		$pub_vars["category"]=(int)$_POST["folder"];
		$pub_vars["title"]=$title;
		$pub_vars["description"]="";
		$pub_vars["keywords"]="";
		$pub_vars["userid"]=(int)$_SESSION["people_id"];
		
		if($global_settings["moderation"])
		{
			$approved=0;
		}
		else
		{
			$approved=1;
		}
		
		$pub_vars["published"]=$approved;
		$pub_vars["viewed"]=0;
		$pub_vars["data"]=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
		$pub_vars["author"]=result($_SESSION["people_login"]);
		$pub_vars["content_type"]=$global_settings["content_type"];
		$pub_vars["downloaded"]=0;
		$pub_vars["model"]=0;
		$pub_vars["examination"]=$exam;
		$pub_vars["server1"]=$site_server_activ;
		$pub_vars["free"]=$free;
		$pub_vars["category2"]=(int)$_POST["folder2"];
		$pub_vars["category3"]=(int)$_POST["folder3"];

		if($global_settings["google_coordinates"])
		{
			$pub_vars["google_x"]=(float)$_POST["google_x"];
			$pub_vars["google_y"]=(float)$_POST["google_y"];
		}
		else
		{
			$pub_vars["google_x"]=0;
			$pub_vars["google_y"]=0;
		}

		if(isset($_POST["editorial"]))
		{
			$pub_vars["editorial"]=1;
		}
		else
		{
			$pub_vars["editorial"]=0;
		}
		
		if(isset($_POST["adult"]))
		{
			$pub_vars["adult"]=1;
		}
		else
		{
			$pub_vars["adult"]=0;
		}

		//Add a new photo to the database
		$id=publication_photo_add();

		//Folder
		$folder=$id;
		
		


		//upload file for sale
		if(filesize($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$afiles[$n])>0)
		{
			$photo=site_root.$site_servers[$site_server_activ]."/".$folder."/".$afiles[$n];
			$size = getimagesize ($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$afiles[$n]);

			//Copy original photo
			@copy($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$afiles[$n],$_SERVER["DOCUMENT_ROOT"].$photo);
			
			$sql="update photos set url_jpg='".result($afiles[$n])."' where id_parent=".$id;
			$db->execute($sql);

			//Copy thumb1
			if(file_exists($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/thumbnail/".$afiles[$n]))
			{
				@copy($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/thumbnail/".$afiles[$n],$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb1.jpg");
			}
			else
			{
				photo_resize($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$afiles[$n],$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb1.jpg",1);
			}

			//Copy thumb2
			photo_resize($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$afiles[$n],$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb2.jpg",2);
	
			publication_watermark_add($id,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb2.jpg");
			
			
			//Other formats
			$filename=get_file_info($afiles[$n],"filename");
			foreach ($photo_formats as $key => $value) 
			{
				$filecopy="";
				
				if($value=="tiff")
				{
					if(file_exists($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$filename.".tif"))
					{
						copy($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$filename.".tif",$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".$filename.".tif");
						$filecopy=$filename.".tif";
					}
					if(file_exists($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$filename.".tiff"))
					{
						copy($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$filename.".tiff",$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".$filename.".tiff");
						$filecopy=$filename.".tiff";
					}
				}
				else
				{
					if(file_exists($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$filename.".".$value))
					{
						copy($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$filename.".".$value,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".$filename.".".$value);
						$filecopy=$filename.".".$value;
					}
				}
				
				if($filecopy!="")
				{
					$sql="update photos set url_".$value."='".result($filecopy)."' where id_parent=".$id;
					$db->execute($sql);
				}
			}
			
			

			$swait=true;
		}

		if($photo!="")
		{
			//IPTC support
			publication_iptc_add($id,$DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$afiles[$n]);
		
			//Rights managed
			if(isset($_POST["license_type"]) and (int)$_POST["license_type"]==1)
			{
				if(isset($_POST["rights_id"]))
				{
					$sql="update photos set rights_managed=".(int)@$_POST["rights_id"]." where id_parent=".$id;
					$db->execute($sql);
					
					//Create photo sizes
					publication_photo_sizes_add($id,$afiles[$n],false,"rights_managed",(int)@$_POST["rights_id"]);
				}
			}
			else
			{
				//Create photo sizes
				publication_photo_sizes_add($id,$afiles[$n],false);
			}
		}

		//prints
		if($global_settings["prints_users"])
		{
			publication_prints_add($id,false);
		}
		
		//Models
		$sql="delete from models_files where publication_id=".$id;
		$db->execute($sql);

		foreach ($_POST as $key => $value) 
		{
			if(preg_match("/model/i",$key))
			{
				$model_id=str_replace("model","",$key);
		
				if($model_id!="")
				{
					$sql="insert into models_files (publication_id,model_id,models) value (".$id.",".(int)$model_id.",".(int)$value.")";
					$db->execute($sql);
				}
			}
		}
		//End. Models

		//End upload files
	}
}






remove_files_from_folder($tmp_folder);



if($global_settings["examination"] and $_SESSION["people_exam"]!=1)
{
	$rurl="upload.php";
}
else
{
	$rurl="publications.php?d=2&t=1";
}

redirect_file($rurl,$swait);
}
$db->close();
?>