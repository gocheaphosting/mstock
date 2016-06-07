<?
$site="languages";
include("../admin/function/db.php");
?>
<?include("../inc/header.php");?>
<div class="page_internal">
<h1><?=word_lang("languages")?></h1>

<div id="lang_box">
<ul>
<?
$lang_list="";

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


	$lang_list.="<li><a href='".site_root."/members/language.php?lang=".$key."'><img src='".site_root."/admin/images/languages/".$lng3.".gif'>".$key."</a></li>";
}
echo($lang_list);
?>
</ul>
</div>
</div>
<?include("../inc/footer.php");?>