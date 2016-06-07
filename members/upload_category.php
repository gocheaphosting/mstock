<?include("../admin/function/db.php");?>
<? include("../admin/function/upload.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?if($global_settings["userupload"]==0){header("location:profile.php");}?>
<?
$sql="select * from user_category where name='".result($_SESSION["people_category"])."'";
$dn->open($sql);
if(!$dn->eof and $dn->row["category"]==1)
{
	$sql="select id_parent,upload from category where id_parent=".(int)$_POST["folder"];
	$ds->open($sql);
	if(!$ds->eof and $ds->row["upload"]==1)
	{
		//If the category is new
		$id=0;
		if(isset($_GET["id"])){$id=(int)$_GET["id"];}

		$_POST["category"]=(int)$_POST["folder"];
		
		$approved = 1;
		
		if($global_settings["moderation"])
		{
			$approved = 0;
		}
		
		if($id!=(int)$_POST["folder"])
		{
			$swait=add_update_category($id,(int)$_SESSION["people_id"],1,$approved);
		}
		else
		{
			$swait=false;
		}

		$smarty->clearCache(null,"buildmenu");
		if($id!=0)
		{
			category_url($id);
		}

		redirect_file("publications.php?d=1&t=1",$swait);
	}
}

$db->close();
?>