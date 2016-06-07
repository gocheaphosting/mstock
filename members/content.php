<?
if(!defined("site_root")){exit();}
include("admin/function/show.php");
include("members_menu.php");

if(count($_POST)==0 and (count($_GET)==0 or isset($_GET["template"])))
{
	$homepage="";
	if (!$smarty->isCached('home.tpl',cache_id('home')) or $site_cache_home<0)
	{
		$homepage=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."home.tpl");
		include("$DOCUMENT_ROOT/members/homepage.php");
	}
	
	if($site_cache_home>=0)
	{
		if($site_cache_home>0)
		{
			$smarty->cache_lifetime = $site_cache_home*3600;
		}
		$smarty->assign('home', $homepage);
		$homepage=$smarty->fetch('home.tpl',cache_id('home'));
	}
	
	echo(translate_text($homepage));
}
else
{
	if(!isset($_GET["stock_api"]))
	{
		if(!check_password(0,$id_parent,0))
		{
			$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."item_protected.tpl");
			$boxcontent=str_replace("{WORD_PROTECTED}",word_lang("password protected"),$boxcontent);
			$boxcontent=str_replace("{ID_PARENT}",$id_parent,$boxcontent);
			$boxcontent=str_replace("{SITE_ROOT}",site_root."/",$boxcontent);
			$boxcontent=str_replace("{TEMPLATE_ROOT}",site_root."/".$site_template_url,$boxcontent);
			echo($boxcontent);
			$boxcontent="";
		}
		else
		{
			if($module_table==30 or $module_table==31 or $module_table==52 or  $module_table==53)
			{				
				if($module_table==30 )
				{
					//Show photo item
					$atype="photo";
					include("content_js_items.php");
					include("content_photo.php");		
				}
				if($module_table==31 )
				{
					//Show video item
					$atype="video";
					include("content_js_items.php");
					include("content_video.php");
				}
				if($module_table==52 )
				{
					//Show audio item
					$atype="audio";
					include("content_js_items.php");
					include("content_audio.php");
				}
				if($module_table==53 )
				{
					//Show vector item
					$atype="vector";
					include("content_js_items.php");
					include("content_vector.php");
				}
				
			}
			else
			{
				include("content_js_listing.php");
	
				//Show item list
				include("content_list.php");
			}
		}
	}
	else
	{
		include("content_js_stock.php");
		
		if(isset($_GET["shutterstock"]))
		{
			include("content_shutterstock.php");
		}
		if(isset($_GET["fotolia"]))
		{
			include("content_fotolia.php");
		}
		if(isset($_GET["istockphoto"]))
		{
			include("content_istockphoto.php");
		}
		if(isset($_GET["depositphotos"]))
		{
			include("content_depositphotos.php");
		}
		if(isset($_GET["rf123"]))
		{
			include("content_123rf.php");
		}
		if(isset($_GET["bigstockphoto"]))
		{
			include("content_bigstockphoto.php");
		}
	}
}
?>