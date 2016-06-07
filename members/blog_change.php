<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?
//Upload function
include("../admin/function/upload.php");

$swait=false;

if($_POST["title"]!="")
{
	$sql="select id_parent,title,content,data,user,published,photo,categories,comments from blog where user='".result($_SESSION["people_login"])."' and id_parent=".(int)$_POST["id"];
	$dr->open($sql);
	
	if(!$dr->eof)
	{
		$img=$dr->row["photo"];
		$_FILES['photo']['name']=result_file($_FILES['photo']['name']);
		$nf=explode(".",strtolower($_FILES['photo']['name']));
		
		if($_FILES['photo']['size']>0 and $_FILES['photo']['size']<2048*1024)
		{
			if((strtolower($nf[count($nf)-1])=="jpeg" or strtolower($nf[count($nf)-1])=="jpg" or strtolower($nf[count($nf)-1])=="gif" or strtolower($nf[count($nf)-1])=="png") and !preg_match("/text/",$_FILES['photo']['type']))
			{
				$img=site_root."/content/blog/post_".(int)$_POST["id"].".".$nf[count($nf)-1];
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


		$sql="update blog set title='".result($_POST["title"])."',content='".result_html_forward($_POST["content"])."',categories='".$categories."',published=".(int)$_POST["published"].",photo='".result($img)."',comments=".$comments." where user='".result($_SESSION["people_login"])."' and id_parent=".(int)$_POST["id"];
		$db->execute($sql);

		$sql="update structure set name='".result($_POST["title"])."' where id=".(int)$_POST["id"];
		$db->execute($sql);
	}
}


$db->close();


redirect_file("blog_posts.php",$swait);



?>