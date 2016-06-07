<?
if(!defined("site_root")){exit();}

$box_search=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."box_search.tpl");

$box_search=str_replace("{WORD_SEARCH}",word_lang("search"),$box_search);

$box_search=format_layout($box_search,"sitephoto",$global_settings["allow_photo"]);
$box_search=format_layout($box_search,"sitevideo",$global_settings["allow_video"]);
$box_search=format_layout($box_search,"siteaudio",$global_settings["allow_audio"]);
$box_search=format_layout($box_search,"sitevector",$global_settings["allow_vector"]);


$bsearch="";
if(isset($_GET["search"])){$bsearch=$_GET["search"];}
if(isset($_POST["search"])){$bsearch=$_POST["search"];}
$box_search=str_replace("{SEARCH}",result($bsearch),$box_search);




$search_types=array();
$xsearch=0;

if($global_settings["allow_photo"]){$search_types["photo"]=1;$xsearch++;}
if($global_settings["allow_video"]){$search_types["video"]=1;$xsearch++;}
if($global_settings["allow_audio"]){$search_types["audio"]=1;$xsearch++;}
if($global_settings["allow_vector"]){$search_types["vector"]=1;$xsearch++;}

$box_search=str_replace("{XSEARCH}",strval($xsearch),$box_search);



if(!isset($_GET["sphoto"]) and !isset($_POST["sphoto"]) and $global_settings["allow_photo"]==1)
{
	$search_types["photo"]=0;
}

if(!isset($_GET["svideo"]) and !isset($_POST["svideo"]) and $global_settings["allow_video"]==1)
{
	$search_types["video"]=0;
}


if(!isset($_GET["saudio"]) and !isset($_POST["saudio"]) and $global_settings["allow_audio"]==1)
{
	$search_types["audio"]=0;
}


if(!isset($_GET["svector"]) and !isset($_POST["svector"]) and $global_settings["allow_vector"]==1)
{
	$search_types["vector"]=0;
}

$sformat=0;
foreach ($search_types as $key => $value)
{
	$sformat+=$value;
}


if($sformat==0)
{
	foreach ($search_types as $key => $value)
	{
		$search_types[$key] = 1;
	}
}


$xx=0;
foreach ($search_types as $key => $value)
{
	if($value==1)
	{
		$box_search=str_replace("{".strtoupper($key)."_CHECKED}","checked",$box_search);
		$xx++;
	}
	else
	{
		$box_search=str_replace("{".strtoupper($key)."_CHECKED}","",$box_search);
	}
}

if($xx==0)
{
	$xx=$xsearch;
}



if($xx==$xsearch)
{
	$box_search=str_replace("{WORD_ALL}",word_lang("All files"),$box_search);
}
else
{
	$box_search=str_replace("{WORD_ALL}",word_lang("files").": ".$xx,$box_search);
}



$box_search=str_replace("{WORD_TYPES}",word_lang("files"),$box_search);

$box_search=translate_text($box_search);


$file_template=str_replace("{BOX_SEARCH}",$box_search,$file_template);
?>