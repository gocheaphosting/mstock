<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("pages_textpages");

$siteinfo=0;
if(isset($_POST["siteinfo".$rs->row["id_parent"]]))
{
	$siteinfo=1;
}	

//If the category is new
if(isset($_GET["id"]) and (int)$_GET["id"]!=0)
{
	$sql="update pages set title='".result($_POST["title"])."',priority=".(int)$_POST["priority"].",content='".result_html($_POST["content"])."',siteinfo=".$siteinfo.",link='".result($_POST["link"])."' where id_parent=".(int)$_GET["id"];
	$db->execute($sql);
	
	page_url((int)$_GET["id"]);
}
else
{	
	$sql="insert into pages (title,content,priority,link,siteinfo) values ('".result($_POST["title"])."','".result_html($_POST["content"])."',".(int)$_POST["priority"].",'".result($_POST["link"])."',".$siteinfo.")";
	$db->execute($sql);
	
	$sql="select id_parent from pages where title='".result($_POST["title"])."' order by id_parent desc";
	$rs->open($sql);
	if(!$rs->eof)
	{
		page_url($rs->row["id_parent"]);
	}
}

unset($_SESSION["site_info_content"]);

$db->close();

header("location:index.php");
?>