<?include("../admin/function/db.php");?>
<?



require("../admin/rackspace/cloudfiles.php");



if($global_settings["rackspace"]==0){exit();}




//Rackspace server
$rackspace_server=0;

//Delete files massive
$items_files=array();
$file_in=array();
$file_out=array();
$preview_in=array();
$preview_out=array();


//Define all publications from the local server
$sql_local="";
$sql="select id from filestorage where types=1";
$rs->open($sql);
if(!$rs->eof)
{	
		$rackspace_server=$rs->row["id"];
}




if($rackspace_server==0)
{
	exit();
}


	$auth = new CF_Authentication($global_settings["rackspace_username"],$global_settings["rackspace_api_key"]);
	$auth->authenticate();
	
	$conn = new CF_Connection($auth);
	$container_files = $conn->create_container($global_settings["rackspace_prefix"]."_files");
	$url_files=$container_files->make_public(3600*24);

	$container_previews = $conn->create_container($global_settings["rackspace_prefix"]."_previews");
	$url_previews=$container_previews->make_public(3600*24);





//Delete removed files from the clouds server
$sql="select filename2,item_id,filename1,id_parent,pdelete from filestorage_files where pdelete=1";
$rs->open($sql);
while(!$rs->eof)
{
	if($rs->row["item_id"]==0)
	{
		$container_previews = $conn->get_container($global_settings["rackspace_prefix"]."_previews");
		$bday = $container_previews->delete_object($rs->row["filename2"]);
	}
	else
	{
		$container_previews = $conn->get_container($global_settings["rackspace_prefix"]."_files");
		$bday = $container_previews->delete_object($rs->row["filename2"]);
	}
	
	
	$rs->movenext();
}

$sql="delete from filestorage_files where pdelete=1";
$db->execute($sql);
//End. Delete removed files from the clouds server




//Select all publications
$sql="(select a.id,a.id_parent as idp,a.module_table,b.id_parent,b.title as atitle,b.data as adata,b.published,b.server1,b.server2 from structure a,photos b where b.published=1 and a.id=b.id_parent  and b.server2=0) union (select a.id,a.id_parent as idp,a.module_table,b.id_parent,b.title as atitle,b.data as adata,b.published,b.server1,b.server2 from structure a,videos b where b.published=1 and a.id=b.id_parent and b.server2=0) union (select a.id,a.id_parent as idp,a.module_table,b.id_parent,b.title as atitle,b.data as adata,b.published,b.server1,b.server2 from structure a,audio b where b.published=1 and a.id=b.id_parent and b.server2=0) union (select a.id,a.id_parent as idp,a.module_table,b.id_parent,b.title as atitle,b.data as adata,b.published,b.server1,b.server2 from structure a,vector b where b.published=1 and a.id=b.id_parent and b.server2=0) order by adata desc limit 0,10";
//echo($sql);
$rs->open($sql);
while(!$rs->eof)
{

	if($rs->row["module_table"]==30)
	{
		$sql="update photos set server2=".$rackspace_server." where id_parent=".$rs->row["id"];
	}
	if($rs->row["module_table"]==31)
	{
		$sql="update videos set server2=".$rackspace_server." where id_parent=".$rs->row["id"];
	}
	if($rs->row["module_table"]==52)
	{
		$sql="update audio set server2=".$rackspace_server." where id_parent=".$rs->row["id"];
	}
	if($rs->row["module_table"]==53)
	{
		$sql="update vector set server2=".$rackspace_server." where id_parent=".$rs->row["id"];
	}
	$db->execute($sql);
	

	$message_log="";

	$publication_path=$_SERVER["DOCUMENT_ROOT"].site_root.server_url($rs->row["server1"])."/".$rs->row["id_parent"];
	//echo($publication_path."<br>");
	
	//Define items for every publication
	$items_mass=array();
	if($rs->row["module_table"]==30)
	{
		$sql="select url_jpg,url_png,url_gif,url_raw,url_tiff,url_eps from photos where id_parent=".$rs->row["id"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			if($ds->row["url_jpg"]!=""){$items_mass[$ds->row["url_jpg"]]=-1;}
			if($ds->row["url_png"]!=""){$items_mass[$ds->row["url_png"]]=-1;}
			if($ds->row["url_gif"]!=""){$items_mass[$ds->row["url_gif"]]=-1;}
			if($ds->row["url_raw"]!=""){$items_mass[$ds->row["url_raw"]]=-1;}
			if($ds->row["url_tiff"]!=""){$items_mass[$ds->row["url_tiff"]]=-1;}
			if($ds->row["url_eps"]!=""){$items_mass[$ds->row["url_eps"]]=-1;}
		}
	}
	else
	{
		$sql="select id,url from items where id_parent=".$rs->row["id"]." and shipped<>1";
		$ds->open($sql);
		if(!$ds->eof)
		{
			while(!$ds->eof)
			{
				if($ds->row["url"]!="")
				{
					$items_mass[$ds->row["url"]]=$ds->row["id"];
				}
				$ds->movenext();
			}
		}
	}
	
	//View publication's folders
	$dir = opendir ($publication_path);
  			while ($file = readdir ($dir)) 
  			{
				if($file <> "." && $file <> ".." && $file <> '.DS_Store' && $file <> 'index.html')
    			{
					//echo($publication_path."/".$file."<br>");
					$items_files[]=$publication_path."/".$file;
					
					$width=0;
					$height=0;
					if(preg_match("/\.jpg$/i",$file) or preg_match("/\.jpeg$/i",$file) or preg_match("/\.png$/i",$file) or preg_match("/\.gif$/i",$file))
					{
						$size = getimagesize($publication_path."/".$file);
						$width=$size[0];
						$height=$size[1];
					}
					
					if(preg_match("/thumb/i",$file)) 
					{ 
						$new_filename=$rs->row["id"]."_".$file;
						$preview_in[]=$publication_path."/".$file;
						$preview_out[]=$new_filename;
		
						$message_log.="The file ".$file." has been moved to Rackspace<br>";
						
						$sql="select id_parent from filestorage_files where id_parent=".$rs->row["id"]." and item_id=0 and filename1='".$file."'";
						$ds->open($sql);
						if($ds->eof)
						{
							$sql="insert into filestorage_files (id_parent,item_id,url,filename1,filename2,filesize,server1,pdelete,width,height) values (".$rs->row["id"].",0,'".$url_previews."','".$file."','".$new_filename."',".filesize($publication_path."/".$file).",".$rackspace_server.",0,".$width.",".$height.")";
							$db->execute($sql);
						}
					}
					else
					{
						//Define extention
						$file_mass=explode(".",$file);
						$file_extention=$file_mass[count($file_mass)-1];
						
						$new_filename=$rs->row["id"]."_".md5(create_password().$rs->row["id"].create_password()).".".$file_extention;
						
						$file_in[]=$publication_path."/".$file;
						$file_out[]=$new_filename;
						

						
						$message_log.="The file ".$file." has been moved to Rackspace<br>";
						
						if(isset($items_mass[$file]))
						{
							$sql="select id_parent from filestorage_files where id_parent=".$rs->row["id"]." and item_id=".$items_mass[$file];
							$ds->open($sql);
							if($ds->eof or $items_mass[$file]==-1)
							{
								$sql="insert into filestorage_files (id_parent,item_id,url,filename1,filename2,filesize,server1,pdelete,width,height) values (".$rs->row["id"].",".$items_mass[$file].",'".$url_files."','".$file."','".$new_filename."',".filesize($publication_path."/".$file).",".$rackspace_server.",0,".$width.",".$height.")";
								$db->execute($sql);
							}
							else
							{
								$sql="update filestorage_files set filename1='".$file."',filename2='".$new_filename."',url='".$url_files."',filesize=".filesize($publication_path."/".$file).",width=".$width.",height=".$height." where id_parent=".$rs->row["id"]." and item_id=".$items_mass[$file];
								$db->execute($sql);
							}
						}
					}
    			}
 			 }
  			closedir ($dir);
	

	
	//Logs
	$sql="insert into filestorage_logs (publication_id,logs,data) values (".$rs->row["id"].",'".$message_log."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).")";
	$db->execute($sql);
	
	
	unset($items_mass);
	$rs->movenext();
}

$flag_delete=false;



//Move previews to Rackspace
for($i=0;$i<count($preview_in);$i++)
{
	$container_previews = $conn->get_container($global_settings["rackspace_prefix"]."_previews");
	$bday = $container_previews->create_object($preview_out[$i]);
	$bday->load_from_filename($preview_in[$i]);
}

//Move files to Rackspace
for($i=0;$i<count($file_in);$i++)
{
	$container_files = $conn->get_container($global_settings["rackspace_prefix"]."_files");
	$bday = $container_files->create_object($file_out[$i]);
	$bday->load_from_filename($file_in[$i]);
	
	if($i==count($file_in)-1)
	{
		$flag_delete=true;
	}
}

sleep(10);

//delete files from the local server
if($flag_delete)
{
	for($i=0;$i<count($items_files);$i++)
	{
		if($items_files[$i]!="")
		{
			@unlink($items_files[$i]);
		}
	}
}

$db->close();
?>