<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_administrators");

$id=0;



if(isset($_GET["id"]))
{
		$password="";
		if($_POST["p"]!="********")
		{
			$password=",password='".md5(result($_POST["p"]))."'";
		}
		
		$sql="update people set login='".result($_POST["l"])."'".$password.",name='".result($_POST["name"])."' where id=".(int)$_GET["id"];
		if(!$demo_mode)
		{
			$db->execute($sql);
		}
		
		$id=(int)$_GET["id"];
}
else
{
	if($_POST["p"]==$_POST["p2"])
	{
		$sql="insert into people (login,password,name) values ('".result($_POST["l"])."','".md5(result($_POST["p"]))."','".result($_POST["name"])."')";
		$db->execute($sql);
		
		$sql="select id from people where login='".result($_POST["l"])."' order by id desc";
		$rs->open($sql);
		$id=$rs->row["id"];
	}
}

		
	//Add rights
	if($id!=0 and !$demo_mode)
	{
		$sql="delete from people_rights where user=".$id;
		$db->execute($sql);
		
		foreach ($submenu_admin as $key => $value) 
		{
			if(isset($_POST[$key]))
			{
				$sql="insert into people_rights (user,user_rights) values (".$id.",'".$key."')";
				$db->execute($sql);	
			}
		}
	}
	
	
	//Upload photos
	$images_types=array("photo");
	
	
	for($i=0;$i<count($images_types);$i++)
	{
		$_FILES[$images_types[$i]]['name']=result_file($_FILES[$images_types[$i]]['name']);
		if($_FILES[$images_types[$i]]['size']>0)
		{
			if(preg_match("/jpg$/i",$_FILES[$images_types[$i]]['name']) and !preg_match("/text/i",$_FILES[$images_types[$i]]['type']))
			{
				$folder="users";
				
				$file_name="admin_".$id;

	
	
				$img=site_root."/content/".$folder."/".$file_name.".jpg";
				move_uploaded_file($_FILES[$images_types[$i]]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$img);
	
	
				easyResize($_SERVER["DOCUMENT_ROOT"].$img,$_SERVER["DOCUMENT_ROOT"].$img,100,$global_settings["userphotowidth"]);
	
				$sql="update people set ".$images_types[$i]."='".result($img)."' where id=".(int)$id;
				$db->execute($sql);
			}
		}
	}

$db->close();
	
header("location:index.php");
?>
