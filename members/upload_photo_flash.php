<?php
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}
include("../admin/function/db.php");
if(!isset($_SESSION["people_id"])){exit;}
if($global_settings["userupload"]==0){exit;}
$sql="select * from user_category where name='".result($_SESSION["people_category"])."'";
$dn->open($sql);
if(!$dn->eof and $dn->row["upload"]==1)
{
$lphoto=$dn->row["photolimit"];



	// Check the upload
	// Check the upload
	if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
		echo "ERROR:invalid upload";
		exit(0);
	}


	if (!isset($_SESSION["file_info"])) {
		$_SESSION["file_info"] = array();
	}
	
	
$tmp_folder="user_".(int)$_SESSION["people_id"];
if(!file_exists($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder))
{
mkdir($DOCUMENT_ROOT.site_upload_directory."/".$tmp_folder);
}
	
	
	//$fileName = md5(rand()*10000000) . ".jpg";
	
$fileName = result_file($_FILES["Filedata"]["name"]);

$fileName=preg_replace("/\.jpg/i","",$fileName);
$title=$fileName;	
	

if(file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".$fileName.".jpg"))
{	
//$sql="select size from sizes order by priority";
//$rs->open($sql);	
//$j=$rs->rc+3;	
$j=3;
for($i=1;$i<$j;$i++)
{
if(!file_exists($_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/".$fileName."_thumb".$i.".jpg"))
{	
$title=$fileName."_thumb".$i;
break;
}
}
}	

	
$flag=true;	
	
if(preg_match("/text/i",$_FILES["Filedata"]["type"]))
{
$flag=false;
}
if(!preg_match("/\.jpg$/i",$_FILES["Filedata"]["name"]))
{
$flag=false;
}


	if($title!="")
	{
if($_FILES["Filedata"]["size"]>0 and $_FILES["Filedata"]["size"]<1024*1024*$lphoto)
{
if($flag==true)
{
	move_uploaded_file($_FILES["Filedata"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"].site_root."/content/".$tmp_folder."/" . $title.".jpg");
	}
	}
	}

	$file_id = md5(rand()*10000000);
	
	$_SESSION["file_info"][$file_id] = $fileName;
ob_clean();
ob_end_flush();
echo "FILEID:" . $file_id;	// Return the file id to the script
	
}
$db->close();
?>