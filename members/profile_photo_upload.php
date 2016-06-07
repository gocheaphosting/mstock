<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");exit();}?>


<?
//Upload function
include("../admin/function/upload.php");

$_FILES[$_GET["type"]]['name']=result_file($_FILES[$_GET["type"]]['name']);
$nf=explode(".",strtolower($_FILES[$_GET["type"]]['name']));
if($_FILES[$_GET["type"]]['size']>0 and $_FILES[$_GET["type"]]['size']<2*1024*1024)
{
	if(($nf[count($nf)-1]=="jpeg" or $nf[count($nf)-1]=="jpg" or $nf[count($nf)-1]=="gif" or $nf[count($nf)-1]=="png") and !preg_match("/text/i",$_FILES[$_GET["type"]]['type']))
	{
		if($_GET["type"]=="avatar"){$ff="avatars";}
		else{$ff="users";}

		$img=site_root."/content/".$ff."/".result($_GET["type"])."_".result($_SESSION["people_login"]).".".$nf[count($nf)-1];
		move_uploaded_file($_FILES[$_GET["type"]]['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$img);

		if($_GET["type"]=="avatar" and $nf[count($nf)-1]=="jpg")
		{
			easyResize($_SERVER["DOCUMENT_ROOT"].$img,$_SERVER["DOCUMENT_ROOT"].$img,100,$global_settings["avatarwidth"]);
		}

		if($_GET["type"]=="photo" and $nf[count($nf)-1]=="jpg")
		{
			easyResize($_SERVER["DOCUMENT_ROOT"].$img,$_SERVER["DOCUMENT_ROOT"].$img,100,$global_settings["userphotowidth"]);
		}

		$sql="update users set ".result3($_GET["type"])."='".result($img)."' where id_parent=".(int)$_SESSION["people_id"];
		$db->execute($sql);
	}
}

$db->close();

redirect_file("profile_about.php",true);
?>