<? include("../function/db.php");?>
<? include("../function/upload.php");?>
<?
//Check access
admin_panel_access("pages_news");

	

//If the category is new
if(isset($_GET["id"]) and (int)$_GET["id"]!=0)
{
	$sql="update news set title='".result($_POST["title"])."',announce='".result_html($_POST["announce"])."',content='".result_html($_POST["content"])."',data=".mktime((int)$_POST["data_hour"],(int)$_POST["data_minute"],(int)$_POST["data_second"],(int)$_POST["data_month"],(int)$_POST["data_day"],(int)$_POST["data_year"])." where id_parent=".(int)$_GET["id"];
	$db->execute($sql);
}
else
{	
	$sql="insert into news (title,announce,content,data) values ('".result($_POST["title"])."','".result_html($_POST["announce"])."','".result_html($_POST["content"])."',".mktime((int)$_POST["data_hour"],(int)$_POST["data_minute"],(int)$_POST["data_second"],(int)$_POST["data_month"],(int)$_POST["data_day"],(int)$_POST["data_year"]).")";
	$db->execute($sql);
}

$db->close();

header("location:index.php");
?>