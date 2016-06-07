<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?if($global_settings["userupload"]==0){header("location:profile.php");}?>
<?
$sql="select id from galleries where user_id=".(int)$_SESSION["people_id"]." and id=".(int)$_POST["gallery_id"];
$rs->open($sql);
if($rs->eof)
{
	exit();
}

$lphoto=$global_settings["prints_lab_filesize"];

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








//Upload set of photos
if(isset($afiles))
{
	for($n=0;$n<count($afiles);$n++)
	{
			$gallery_folder=(int)$_POST["gallery_id"];
			if(!file_exists($DOCUMENT_ROOT."/content/galleries/".$gallery_folder))
			{
				mkdir($DOCUMENT_ROOT."/content/galleries/".$gallery_folder);
				@copy($DOCUMENT_ROOT."/content/index.html",$DOCUMENT_ROOT."/content/galleries/".$gallery_folder."/index.html");
			}

			$fileName=preg_replace("/\.jpg$/i","",$afiles[$n]);
			$title=str_replace("_","",$fileName);
			
			if(filesize($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$afiles[$n])>0)
			{
				$size = getimagesize ($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$afiles[$n],$info);
				$wd1=$global_settings["thumb_width"];
				if(isset($size[1]))
				{
					if($size[0]<$size[1])
					{
						$wd1=$size[0]*$global_settings["thumb_width"]/$size[1];
					}
				}
				
				if(isset ($info["APP13"]))
				{
					$iptc = iptcparse ($info["APP13"]);

					if(isset($iptc["2#005"][0]) and $iptc["2#005"][0]!="")
					{
						$title=result($iptc["2#005"][0]);
					}
				}

				$photo_path=$_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".$gallery_folder."/thumb_".$afiles[$n];
				@copy($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder."/".$afiles[$n],$photo_path);
							
				$sql="insert into galleries_photos (id_parent,title,photo,data) values (".(int)$_POST["gallery_id"].",'".$title."','thumb_".$afiles[$n]."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).")";
				$db->execute($sql);
				
				$sql="update galleries set data=".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." where id=".(int)$_POST["gallery_id"];
				$db->execute($sql);
				
				$sql="select id from galleries_photos where id_parent=".(int)$_POST["gallery_id"]." order by id desc";
				$rs->open($sql);
				$photo_id=$rs->row["id"];
				easyResize($photo_path,$_SERVER["DOCUMENT_ROOT"].site_root."/content/galleries/".$gallery_folder."/thumb".$photo_id.".jpg",100,$wd1);
			}
	}
}



remove_files_from_folder($tmp_folder);





redirect_file("printslab.php",true);

$db->close();
?>