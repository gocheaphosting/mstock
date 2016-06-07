<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?if($global_settings["userupload"]==0){header("location:profile.php");}?>
<?
//Zip library
include( $_SERVER["DOCUMENT_ROOT"].site_root."/admin/function/pclzip.lib.php" );

//Upload function
include("../admin/function/upload.php");

$sql="select * from user_category where name='".result($_SESSION["people_category"])."'";
$dn->open($sql);
if(!$dn->eof and $dn->row["upload4"]==1)
{
$lvector=$dn->row["vectorlimit"];
$sql="select id_parent,upload from category where id_parent=".(int)$_POST["folder"];
$ds->open($sql);
if(!$ds->eof and $ds->row["upload"]==1)
{

$photo="";
$swait=false;





$remote="";
$flash_width=$global_settings["flash_width"];
$flash_height=$global_settings["flash_height"];
$flash_version="";
$script_version="";
if(isset($_POST["flash_width"]))
{
	$flash_width=(int)$_POST["flash_width"];
}
if(isset($_POST["flash_height"]))
{
	$flash_height=(int)$_POST["flash_height"];
}
if(isset($_POST["flash_version"]))
{
	$flash_version=result($_POST["flash_version"]);
}
if(isset($_POST["script_version"]))
{
	$script_version=result($_POST["script_version"]);
}


//model
$model=0;
if(isset($_POST["model"]))
{
	$model=(int)$_POST["model"];
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





$pub_vars=array();
$pub_vars["category"]=(int)$_POST["folder"];
$pub_vars["title"]=result($_POST["title"]);
$pub_vars["description"]=result($_POST["description"]);
$pub_vars["keywords"]=result($_POST["keywords"]);
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
$pub_vars["model"]=$model;
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

		if(isset($_POST["adult"]))
		{
			$pub_vars["adult"]=1;
		}
		else
		{
			$pub_vars["adult"]=0;
		}


$pub_vars["flash_version"]=$flash_version;
$pub_vars["script_version"]=$script_version;
$pub_vars["flash_width"]=$flash_width;
$pub_vars["flash_height"]=$flash_height;



if(!isset($_GET["id"]))
{
	//Add a new vector to the database
	$id=publication_vector_add();
}
else
{
	$id=(int)$_GET["id"];
	$sql="select downloaded,viewed,data,content_type from vector where id_parent=".$id;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$pub_vars["downloaded"]=$rs->row["downloaded"];
		$pub_vars["viewed"]=$rs->row["viewed"];
		$pub_vars["data"]=$rs->row["data"];
		$pub_vars["content_type"]=$rs->row["content_type"];
	}
	//Update a vector into the database
	publication_vector_update($id,(int)$_SESSION["people_id"]);
	
	$smarty->clearCache(null,"item|".$id);
	$smarty->clearCache(null,"share|".$id);
	item_url($id);
}


//Folder
$folder=$id;









//upload file for sale
if(!isset($_GET["id"]))
{
	publication_files_upload($id,"vector");
	$swait=true;
}
else
{
	price_update((int)$_GET["id"],"vector");
	$folder=(int)$_GET["id"];
	publication_files_upload($id,"vector");
	
	//Rights managed
	if(isset($_POST["license_type"]) and (int)$_POST["license_type"]==1)
	{
		if(isset($_POST["rights_id"]))
		{
			$sql="update vector set rights_managed=".(int)@$_POST["rights_id"]." where id_parent=".$id;
			$db->execute($sql);
		}
	}
}





if($global_settings["examination"] and $_SESSION["people_exam"]!=1)
{
	$rurl="upload.php";
}
else
{
	$rurl="publications.php?d=5&t=1";
}


//go to back
redirect_file($rurl,$swait);





}
}

$db->close();
?>