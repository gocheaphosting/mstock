<?$site="user_blog";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>




<?include("user_top.php");?>

<?
$blog_page="<table border='0' cellpadding='0' cellspacing='0' width='100%'>
<tr valign='top'>
<td width='90%'>{RESULTS}</td>
<td width='145' style='padding-left:30px'>{MENU}</td> 
</tr>
</table>";

if(file_exists($DOCUMENT_ROOT."/".$site_template_url."blog.tpl"))
{
	$blog_page=file_get_contents($DOCUMENT_ROOT."/".$site_template_url."blog.tpl");
}

$blog_page=str_replace("{MENU}","{SEPARATOR}",$blog_page);
$blog_page=str_replace("{RESULTS}","{SEPARATOR}",$blog_page);

$blog_parts=explode("{SEPARATOR}",$blog_page);

$blog_header=$blog_parts[0];
$blog_middle=@$blog_parts[1];
$blog_footer=@$blog_parts[2];

echo($blog_header);

include("user_blog_content.php");

echo($blog_middle);

include("user_blog_menu.php");

echo($blog_footer);
?>












<?include("user_bottom.php");?>






















<?include("../inc/footer.php");?>