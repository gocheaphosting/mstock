<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");exit();}?>


<?
//Upload function
include("../admin/function/upload.php");

$com="";
if($_SESSION["people_type"]=="buyer" or $_SESSION["people_type"]=="common")  
{
	$com=" and buyer=1 ";
}
if($_SESSION["people_type"]=="seller" or $_SESSION["people_type"]=="common")  
{
	$com=" and seller=1 ";
}
if($_SESSION["people_type"]=="affiliate" or $_SESSION["people_type"]=="common")  
{
	$com=" and affiliate=1 ";
}

$sql="select title,description,filesize from documents_types where enabled=1 ".$com." and id=".(int)$_POST["document_type"];
$rs->open($sql);
if(!$rs->eof)
{
	$_FILES["document_file"]['name']=result_file($_FILES["document_file"]['name']);
	
	if($_FILES["document_file"]['size']>0 and $_FILES["document_file"]['size']<$rs->row["filesize"]*1024*1024)
	{
		$file_filename=get_file_info($_FILES["document_file"]['name'],"filename");
		$file_extention=strtolower(get_file_info($_FILES["document_file"]['name'],"extention"));
		
		if(($file_extention=="jpg" or $file_extention=="pdf") and !preg_match("/text/i",$_FILES["document_file"]['type']))
		{	
			$sql="insert into documents (id_parent,title,user_id,status,filename,data,comment) values (".(int)$_POST["document_type"].",'".$rs->row["title"]."',".(int)$_SESSION["people_id"].",0,'".$file_filename.".".$file_extention."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'')";
			$db->execute($sql);
			
			$sql="select id from documents where user_id=".(int)$_SESSION["people_id"]." order by data desc";
			$ds->open($sql);
			$id=$ds->row["id"];
			
			$img=site_root."/content/users/doc_".$id."_".$file_filename.".".$file_extention;
			move_uploaded_file($_FILES["document_file"]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$img);
		}
	}
}

$db->close();

redirect_file("profile_about.php",true);
?>