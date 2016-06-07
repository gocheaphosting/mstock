<?$site="page";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>



<?

$boxcontent=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."textpage.tpl");
$boxcontent=str_replace("{SITE_NAME}",$global_settings["site_name"],$boxcontent);

$sql="select title,content from pages where id_parent=".(int)$_GET["id"]." or title='".str_replace("-"," ",result3($_GET["id"]))."'";;
$rs->open($sql);
if(!$rs->eof)
{
	$boxcontent=str_replace("{TITLE}",word_lang($rs->row["title"]),$boxcontent);
	$boxcontent=str_replace("{CONTENT}",translate_text($rs->row["content"]),$boxcontent);
}


echo($boxcontent);
?>


<?include("../inc/footer.php");?>