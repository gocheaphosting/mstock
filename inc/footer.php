<?
if(!defined("site_root")){exit();}
//Footer template
if (!$smarty->isCached('footer.tpl',cache_id('footer')) or $site_cache_footer<0)
{
	$file_template=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."footer.tpl");




	
	//Box stat
	include("box_stat.php");

	//Words
	

	//Site name
	$file_template=str_replace("{SITE_NAME}",$global_settings["site_name"],$file_template);

	//Site root
	$file_template=str_replace("{SITE_ROOT}",site_root."/",$file_template);

	
	$file_template=str_replace("{TELEPHONE}",$global_settings["telephone"],$file_template);
	$file_template=str_replace("{FACEBOOK}",$global_settings["facebook_link"],$file_template);
	$file_template=str_replace("{GOOGLE}",$global_settings["google_link"],$file_template);
	$file_template=str_replace("{TWITTER}",$global_settings["twitter_link"],$file_template);

	//Template root
	$file_template=str_replace("{TEMPLATE_ROOT}",site_root."/".$site_template_url,$file_template);

	$file_template=format_layout($file_template,"sitephoto",$global_settings["allow_photo"]);
	$file_template=format_layout($file_template,"sitevideo",$global_settings["allow_video"]);
	$file_template=format_layout($file_template,"siteaudio",$global_settings["allow_audio"]);
	$file_template=format_layout($file_template,"sitevector",$global_settings["allow_vector"]);
	$file_template=format_layout($file_template,"sitecredits",$global_settings["credits"]);
	$file_template=format_layout($file_template,"sitesubscription",$global_settings["subscription"]);
	$file_template=translate_text($file_template);
	



//End Footer template
}


if($site_cache_footer>=0)
{
	if($site_cache_footer>0)
	{
		$smarty->cache_lifetime = $site_cache_footer*3600;
	}
	$smarty->assign('footer', $file_template);
	$file_template=$smarty->fetch('footer.tpl',cache_id('footer'));
}

echo($file_template);

$db->close();
?>