<?
if(!defined("site_root")){exit();}
$box_languages="";

$box_languages_lite="<script>function lopen(){\$('#languages_lite2').slideDown('slow');}function lclose(){\$('#languages_lite2').slideUp('slow');}</script><div id='languages_lite'><a href='javascript:lopen();'>".$lang_name[$lng]."</a><span>&nbsp;</span></div><div id='languages_lite2'><a href='javascript:lclose();'><img src='".site_root."/".$site_template_url."images/close.png' class='close'></a><ul>";

$box_languages_lite2="<div id='languages_lite'><a href='#' class='lanbox'>".$lang_name[$lng]."</a><span>&nbsp;</span></div><div style='display:none'><div id='languages_lite2'><div id='lightbox_header'>".word_lang("languages")."</div><div><ul>";

$lang_img="";

foreach ($_SESSION["site_lng"] as $key => $value) 
{
	$lt="";
	$sel="selected";
	if($lng!=$key){$lt="2";$sel="";}

	$lng3=strtolower($key);
	if($lng3=="chinese traditional"){$lng3="chinese";}
	if($lng3=="chinese simplified"){$lng3="chinese";}
	if($lng3=="afrikaans formal"){$lng3="afrikaans";}
	if($lng3=="afrikaans informal"){$lng3="afrikaans";}
	
	if($key==$lng)
	{
		$lang_img=site_root."/admin/images/languages/".$lng3.".gif";
	}

	$box_languages.="<option value='".$key."' ".$sel.">".$key."</option>";

	$box_languages_lite.="<li><a href='".site_root."/members/language.php?lang=".$key."'><img src='".site_root."/admin/images/languages/".$lng3.$lt.".gif'>".$key."</a></li>";
	
	$box_languages_lite2.="<li><a href='".site_root."/members/language.php?lang=".$key."'><img src='".site_root."/admin/images/languages/".$lng3.$lt.".gif'>".$key."</a></li>";
}


$box_languages_lite.="</ul></div>";
$box_languages_lite2.="</ul></div></div></div>";


$file_template=str_replace("{LANGUAGES}",$box_languages,$file_template);
$file_template=str_replace("{LANGUAGES_LITE}",$box_languages_lite,$file_template);
$file_template=str_replace("{LANGUAGES_LITE2}",$box_languages_lite2,$file_template);

$file_template=str_replace("{LANG_SYMBOL}",$lang_symbol[$lng],$file_template);
$file_template=str_replace("{LANG_NAME}",$lang_name[$lng],$file_template);
$file_template=str_replace("{LANG_IMG}",$lang_img,$file_template);
?>