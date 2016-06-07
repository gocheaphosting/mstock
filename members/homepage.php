<?
if(!defined("site_root")){exit();}



//components
$out = array();
preg_match_all("|\{COMPONENT_(\d*)\}|U",$homepage, $out);
$k=0;
while(isset($out[0][$k]) and isset($out[1][$k]))
{
	$component_id=(int)$out[1][$k];
	$component_body="";
	include("component.php");
	$homepage=str_replace("{COMPONENT_".strval($out[1][$k])."}",$component_body,$homepage);
	$k++;
}


//Tag clouds
if(isset($box_tag_clouds))
{
	$homepage=str_replace("{BOX_TAG_CLOUDS}",$box_tag_clouds,$homepage);
}


//Stat
if(isset($box_stat))
{
	$homepage=str_replace("{BOX_STAT}",$box_stat,$homepage);
}


//Member
if(isset($box_members))
{
	$homepage=str_replace("{BOX_MEMBERS}",$box_members,$homepage);
}




?>