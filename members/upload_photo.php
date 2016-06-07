<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?if($global_settings["userupload"]==0){header("location:profile.php");}?>
<?
$sql="select * from user_category where name='".result($_SESSION["people_category"])."'";
$dn->open($sql);
if(!$dn->eof and $dn->row["upload"]==1)
{
$lphoto=$dn->row["photolimit"];


//Upload function
include("../admin/function/upload.php");

$photo="";
$swait=false;





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



if(isset($_GET["id"]))
{
	$id=(int)$_GET["id"];
	$sql="select downloaded,viewed,data,content_type,published from photos where id_parent=".$id;
	$rs->open($sql);
	if(!$rs->eof)
	{
		$pub_vars["downloaded"]=$rs->row["downloaded"];
		$pub_vars["viewed"]=$rs->row["viewed"];
		$pub_vars["data"]=$rs->row["data"];
		$pub_vars["content_type"]=$rs->row["content_type"];
		//$pub_vars["published"]=$rs->row["published"];
	}
	//Update a photo into the database
	publication_photo_update($id,(int)$_SESSION["people_id"]);
	price_update($id,"photo");
	$folder=$id;
	publication_photo_upload($id);
	
	$smarty->clearCache(null,"item|".$id);
	$smarty->clearCache(null,"share|".$id);
	item_url($id);
}


//Folder
$folder=$id;




//upload file for sale
if(isset($_GET["id"]))
{
	price_update((int)$_GET["id"],"photo");
}




//Prints
if($global_settings["prints_users"])
{
	if(isset($_GET["id"]))
	{
		prints_update((int)$_GET["id"]);
	}
}

		//Models
		$sql="delete from models_files where publication_id=".(int)$_GET["id"];
		$db->execute($sql);

		foreach ($_POST as $key => $value) 
		{
			if(preg_match("/model/i",$key))
			{
				$model_id=str_replace("model","",$key);
		
				if($model_id!="")
				{
					$sql="insert into models_files (publication_id,model_id,models) value (".(int)$_GET["id"].",".(int)$model_id.",".(int)$value.")";
					$db->execute($sql);
				}
			}
		}
		//End. Models



if($global_settings["examination"] and $_SESSION["people_exam"]!=1)
{
	$rurl="upload.php";
}
else
{
	$rurl="publications.php?d=2&t=1";
}



//go to back
header("location:".$rurl);

}

$db->close();
?>