<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?if($global_settings["userupload"]==0){header("location:profile.php");}?>
<?
//Zip library
include( $_SERVER["DOCUMENT_ROOT"].site_root."/admin/function/pclzip.lib.php" );



$sql="select * from user_category where name='".result($_SESSION["people_category"])."'";
$dn->open($sql);
if(!$dn->eof and $dn->row["upload"]==1)
{



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
		if (preg_match("/.zip$/i",$file)) 
		{ 
			$file=result_file($file);
			$afiles[count($afiles)]=$file;
		}
    }
  }
  closedir ($dir);
sort ($afiles);
reset ($afiles);




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


$fileName=preg_replace("/\.zip$/i","",$afiles[$n]);
$title=preg_replace("/\.jpg$|\.jpeg$/i","",str_replace("_","",$fileName));




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
$archive = new PclZip($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$afiles[$n]);
if ($archive->extract(PCLZIP_OPT_PATH,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder) == true) 
{

	$ext=explode(".",$fileName);
	$filename_old="original.".strtolower($ext[count($ext)-1]);
	

	rename($DOCUMENT_ROOT.$site_servers[$site_server_activ]."/".$folder."/".$filename_old,$DOCUMENT_ROOT.$site_servers[$site_server_activ]."/".$folder."/".$fileName);
	
	$sql="update photos set url_jpg='".result($fileName)."' where id_parent=".$id;
	$db->execute($sql);	
		
	//IPTC support
	publication_iptc_add($id,$DOCUMENT_ROOT.$site_servers[$site_server_activ]."/".$folder."/".$fileName);


	//Rights managed
	if(isset($_POST["license_type"]) and (int)$_POST["license_type"]==1)
	{
		if(isset($_POST["rights_id"]))
		{
			$sql="update photos set rights_managed=".(int)@$_POST["rights_id"]." where id_parent=".$id;
			$db->execute($sql);
					
			//Create photo sizes
			publication_photo_sizes_add($id,$fileName,true,"rights_managed",(int)@$_POST["rights_id"]);
		}
	}
	else
	{
		//Create photo sizes
		publication_photo_sizes_add($id,$fileName,true);
	}
	publication_watermark_add($id,$DOCUMENT_ROOT.$site_servers[$site_server_activ]."/".$folder."/thumb2.jpg");

}
















//prints
if($global_settings["prints_users"])
{
publication_prints_add($id,true);
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







//Remove temp files
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