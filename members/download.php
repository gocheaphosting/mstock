<?
$nolang=true;
if(isset($_GET["f"]))
{
	$nosession=true;
}
include("../admin/function/db.php");
include("download_mimes.php");





//Order photo download
if(isset($_GET["f"]))
{
	$sql="select id_parent,link,data,tlimit,ulimit,publication_id from downloads where link='".result3($_GET["f"])."' and data>".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))." and tlimit<ulimit+1";
	$ds->open($sql);
	if(!$ds->eof)
	{
		$sql="update downloads set tlimit=tlimit+1 where link='".result3($_GET["f"])."'";
		$db->execute($sql);
		
		$publication_id=$ds->row["publication_id"];
		$publication_item=$ds->row["id_parent"];
		
		$sql="select module_table from structure where id=".$publication_id;
		$rs->open($sql);
		if(!$rs->eof)
		{
			if($rs->row["module_table"]==30)
			{
				$publication_type="photo";				
				$sql="select server1 from photos where id_parent=".$ds->row["publication_id"];
			}
			if($rs->row["module_table"]==31)
			{
				$publication_type="video";				
				$sql="select server1 from videos where id_parent=".$ds->row["publication_id"];
			}
			if($rs->row["module_table"]==52)
			{
				$publication_type="audio";				
				$sql="select server1 from audio where id_parent=".$ds->row["publication_id"];
			}
			if($rs->row["module_table"]==53)
			{
				$publication_type="vector";				
				$sql="select server1 from vector where id_parent=".$ds->row["publication_id"];
			}
			$dr->open($sql);
			if(!$dr->eof)
			{
				$publication_server=$dr->row["server1"];
			}
		}
		
		$flag_order=true;
	}
	else
	{
		echo(word_lang("expired"));
		exit();
	}
	
	if($flag_order)
	{
		$download_regime="order";
		include("download_process.php");
	}
	exit();
}
//End. Order photo download









//define folder and filename
$flag=false;
$uu=explode("/",$_GET["u"]);
$publication_id=(int)$uu[count($uu)-2];
$publication_file=result($uu[count($uu)-1]);
$publication_extention=get_file_info($publication_file,"extention");

//define if the publication is remote storage
$flag_storage=false;
$remote_file="";
$remote_filename="";
$remote_extention="";
		
if($global_settings["amazon"] or $global_settings["rackspace"])
{
	$sql="select url,filename1,filename2,width,height,item_id from filestorage_files where id_parent=".$publication_id." and filename1='".$publication_file."'";
	$dr->open($sql);
	if(!$dr->eof)
	{
		$remote_file=$dr->row["url"]."/".$dr->row["filename2"];
		$remote_filename=$dr->row["filename1"];
		$flag_storage=true;
	}
		
	if($flag_storage)
	{
		$remote_ext=explode(".",$remote_file);
		$remote_extention=strtolower($remote_ext[count($remote_ext)-1]);
	}
}



//Define content folder
$server1=1;

$sql="select module_table from structure where id=".$publication_id;
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["module_table"]==30)
	{		
		$sql="select server1 from photos where id_parent=".$publication_id;
	}
	if($rs->row["module_table"]==31)
	{		
		$sql="select server1 from videos where id_parent=".$publication_id;
	}
	if($rs->row["module_table"]==52)
	{		
		$sql="select server1 from audio where id_parent=".$publication_id;
	}
	if($rs->row["module_table"]==53)
	{	
		$sql="select server1 from vector where id_parent=".$publication_id;
	}
	$dr->open($sql);
	if(!$dr->eof)
	{
		$server1=$dr->row["server1"];
	}
}



//Show thumbs
if(preg_match("/thumb|thumbnail|model|avatar|users|blog|categories|xml/",$_GET["u"])){$flag=true;}




//Show freedowload file
if(isset($_SESSION["people_id"]))
{
	$sql="select used from coupons where url='".site_root.server_url($server1)."/".result($_GET["u"])."' and used=0 and user='".result($_SESSION["people_login"])."'";
	$ds->open($sql);
	if(!$ds->eof)
	{
		$flag=true;
	}
}

//Show files in admin panel
if(isset($_SESSION["entry_admin"]))
{
	$flag=true;
}

//Show own files of a photographer
if(isset($_SESSION["people_id"]) and isset($_SESSION["people_login"]))
{
	$sql="select id_parent from photos where id_parent=".$publication_id." and (userid=".(int)$_SESSION["people_id"]." or author='".result($_SESSION["people_login"])."')";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$flag=true;
	}
	
	$sql="select id_parent from videos where id_parent=".$publication_id." and (userid=".(int)$_SESSION["people_id"]." or author='".result($_SESSION["people_login"])."')";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$flag=true;
	}
	
	$sql="select id_parent from audio where id_parent=".$publication_id." and (userid=".(int)$_SESSION["people_id"]." or author='".result($_SESSION["people_login"])."')";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$flag=true;
	}
	
	$sql="select id_parent from vector where id_parent=".$publication_id." and (userid=".(int)$_SESSION["people_id"]." or author='".result($_SESSION["people_login"])."')";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$flag=true;
	}
}




if($flag==true)
{
	if(!$flag_storage)
	{
		if(isset($mmtype[strtolower($publication_extention)]))
		{
			ob_clean();
			header("Content-Type:".$mmtype[strtolower($publication_extention)]);
			header("Content-Disposition: attachment; filename=".str_replace(" ","%20",$publication_file));
			ob_end_flush();
			readfile_chunked ($DOCUMENT_ROOT.server_url($server1)."/".$publication_id."/".$publication_file);
		}
	}
	else
	{
		if(isset($mmtype[$remote_extention]))
		{
			ob_clean();
			header("Content-Type:".$mmtype[$remote_extention]);
			header("Content-Disposition: attachment; filename=".$remote_filename);
			ob_end_flush();
			@readfile($remote_file);
			exit();
		}
	}

	exit();
}
else
{
	header("Content-Type: image/gif");
	readfile($DOCUMENT_ROOT."/content/access_denied.gif");
	exit();
}
?>