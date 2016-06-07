<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_bulkupload");

//Zip library
include( $_SERVER["DOCUMENT_ROOT"].site_root."/admin/function/pclzip.lib.php");

//Upload function
include("../function/upload.php");

$swait=false;


$ids=array();
$sql="select id_parent from vector_types order by priority";
$ds->open($sql);
while(!$ds->eof)
{
$ids[]=$ds->row["id_parent"];
$ds->movenext();
}



for($j=0;$j<$global_settings["bulk_upload"];$j++)
{


$flag_upload=false;
for($i=0;$i<count($ids);$i++)
{
if(isset($_POST["file".$ids[$i]."_".$j]) and $_POST["file".$ids[$i]."_".$j]!=""){$flag_upload=true;}
}





$vector="";




if($flag_upload==true)
{

$title=result($_POST["title".$j]);
if($title=="")
{
	$title="vector".$j;
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
$pub_vars["model"]=(int)$_POST["model".$j];
$pub_vars["examination"]=0;
$pub_vars["server1"]=$site_server_activ;
$pub_vars["free"]=0;
$pub_vars["category2"]=(int)$_POST["category2"];
$pub_vars["category3"]=(int)$_POST["category3"];

$pub_vars["flash_version"]="";
$pub_vars["script_version"]="";
$pub_vars["flash_width"]=$global_settings["flash_width"];
$pub_vars["flash_height"]=$global_settings["flash_height"];

$pub_vars["google_x"]=0;
$pub_vars["google_y"]=0;
$pub_vars["adult"]=0;


//Add a new vector to the database
$id=publication_vector_add();

$folder=$id;




$previewphoto=site_root.$global_settings["vectorpreupload"].result($_POST["previewphoto".$j]);




//Preview flash
$fn=explode(".",strtolower($_POST["previewflash".$j]));
if($_POST["previewflash".$j]!="" and $fn[count($fn)-1]=="swf")
{
	copy($_SERVER["DOCUMENT_ROOT"].site_root.$global_settings["vectorpreupload"].result($_POST["previewflash".$j]),$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb.swf");
}




//Photo preview
$fn=explode(".",strtolower($_POST["previewphoto".$j]));
if($_POST["previewphoto".$j]!="" and ($fn[count($fn)-1]=="jpg" or $fn[count($fn)-1]=="jpeg"))
{

			photo_resize($_SERVER["DOCUMENT_ROOT"].$previewphoto,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb1.jpg",1);

			photo_resize($_SERVER["DOCUMENT_ROOT"].$previewphoto,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb2.jpg",2);

			publication_watermark_add($id,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb2.jpg");

			publication_iptc_add($id,$_SERVER["DOCUMENT_ROOT"].$previewphoto);
			
			copy($_SERVER["DOCUMENT_ROOT"].$previewphoto,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/thumb_original.jpg");
}







//Upload zip preview
$nf=explode(".",$_POST["previewphoto".$j]);
if(strtolower($nf[count($nf)-1])=="zip")
{
	publication_zip_preview($previewphoto);
}





//copy file for sale 
	$sql="select * from vector_types order by priority";
	$ds->open($sql);
	while(!$ds->eof)
	{
		if($ds->row["shipped"]!=1)
		{
			if(isset($_POST["file".$ds->row["id_parent"]."_".$j]) and $_POST["file".$ds->row["id_parent"]."_".$j]!="")
			{
				$vector=site_root.$global_settings["vectorpreupload"].result($_POST["file".$ds->row["id_parent"]."_".$j]);
				copy($_SERVER["DOCUMENT_ROOT"].$vector,$_SERVER["DOCUMENT_ROOT"].site_root.$site_servers[$site_server_activ]."/".$folder."/".result($_POST["file".$ds->row["id_parent"]."_".$j]));
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
















//Prints
if(isset($_POST["prints".$j]))
{
	publication_prints_add($id,false);
}








}
}











$db->close();



//go back
redirect_file("../catalog/index.php?category_id=".(int)$_POST["category"],true);
?>