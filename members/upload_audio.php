<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?if($global_settings["userupload"]==0){header("location:profile.php");}?>
<?
$sql="select * from user_category where name='".result($_SESSION["people_category"])."'";
$dn->open($sql);
if(!$dn->eof and $dn->row["upload3"]==1)
{
$laudio=$dn->row["audiolimit"];
$lpreview=$dn->row["previewaudiolimit"];
$sql="select id_parent,upload from category where id_parent=".(int)$_POST["folder"];
$ds->open($sql);
if(!$ds->eof and $ds->row["upload"]==1)
{

$photo="";
$swait=false;


//Upload function
include("../admin/function/upload.php");


$duration=0;
$format="";
$source="";
$holder="";
if(isset($_POST["format"])){$format=result($_POST["format"]);}
if(isset($_POST["source"])){$source=result($_POST["source"]);}
if(isset($_POST["holder"])){$holder=result($_POST["holder"]);}


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


$pub_vars["duration"]=3600*$_POST["duration_hour"]+60*$_POST["duration_minute"]+(int)$_POST["duration_second"];;
$pub_vars["format"]=$format;
$pub_vars["source"]=$source;
$pub_vars["holder"]=$holder;



if(!isset($_GET["id"]))
{
	//Add a new audio to the database
	$id=publication_audio_add();
}
else
{
	$id=(int)$_GET["id"];
	$sql="select downloaded,viewed,data,content_type from audio where id_parent=".$id;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$pub_vars["downloaded"]=$rs->row["downloaded"];
		$pub_vars["viewed"]=$rs->row["viewed"];
		$pub_vars["data"]=$rs->row["data"];
		$pub_vars["content_type"]=$rs->row["content_type"];
	}
	//Update a audio into the database
	publication_audio_update($id,(int)$_SESSION["people_id"]);
	
	$smarty->clearCache(null,"item|".$id);
	$smarty->clearCache(null,"share|".$id);
	item_url($id);
}


//Folder
$folder=$id;









//upload file for sale
if(!isset($_GET["id"]))
{
	publication_files_upload($id,"audio");
	$swait=true;
}
else
{
	price_update((int)$_GET["id"],"audio");
	$folder=(int)$_GET["id"];
	publication_files_upload($id,"audio");
	
	//Rights managed
	if(isset($_POST["license_type"]) and (int)$_POST["license_type"]==1)
	{
		if(isset($_POST["rights_id"]))
		{
			$sql="update audio set rights_managed=".(int)@$_POST["rights_id"]." where id_parent=".$id;
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
	$rurl="publications.php?d=4&t=1";
}




//go to back
redirect_file($rurl,$swait);





}
}
$db->close();
?>