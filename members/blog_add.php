<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?
//Upload function
include("../admin/function/upload.php");

$swait=false;

if($_POST["title"]!="")
{

	$img="";

	$sql="insert into blog (title,content,user,data,published) values ('".result($_POST["title"])."','".result_html_forward($_POST["content"])."','".result($_SESSION["people_login"])."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",".(int)$_POST["published"].")";
	$db->execute($sql);

	$sql="select id_parent from blog where user='".result($_SESSION["people_login"])."' order by id_parent desc";
	$rs->open($sql);
	$id=$rs->row['id_parent'];



	$_FILES['photo']['name']=result_file($_FILES['photo']['name']);
	$nf=explode(".",strtolower($_FILES['photo']['name']));
	if($_FILES['photo']['size']>0 and $_FILES['photo']['size']<20480*1024)
	{
		if((strtolower($nf[count($nf)-1])=="jpeg" or strtolower($nf[count($nf)-1])=="jpg" or strtolower($nf[count($nf)-1])=="gif" or strtolower($nf[count($nf)-1])=="png") and !preg_match("/text/",$_FILES['photo']['type']))
		{
			$img=site_root."/content/blog/post_".$id.".".$nf[count($nf)-1];
			move_uploaded_file($_FILES['photo']['tmp_name'],$_SERVER["DOCUMENT_ROOT"].$img);

			if(strtolower($nf[count($nf)-1])=="jpg" or strtolower($nf[count($nf)-1])=="jpeg")
			{
				easyResize($_SERVER["DOCUMENT_ROOT"].$img,$_SERVER["DOCUMENT_ROOT"].$img,100,$global_settings["thumb_width"]);
			}

			$swait=true;
		}
	}


	$comments=0;
	if(isset($_POST["comments"]))
	{
		$comments=1;
	}


	$categories="";
	$sql="select id_parent,title,user from blog_categories where user='".result($_SESSION["people_login"])."' order by title";
	$rs->open($sql);
	while(!$rs->eof)
	{
		if(isset($_POST["category".$rs->row["id_parent"]]))
		{
			if($categories!=""){$categories.=",";}
			$categories.=$rs->row["title"];
		}
		$rs->movenext();
	}

	$sql="update blog set categories='".$categories."',photo='".result($img)."',comments=".$comments." where id_parent=".$id;
	$db->execute($sql);


}

$db->close();

redirect_file("blog_posts.php",$swait);



?>