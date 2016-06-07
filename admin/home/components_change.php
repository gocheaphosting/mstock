<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("templates_home");

$slideshow=0;
$slideshowtime=1;
if(isset($_POST["slideshow"])){$slideshow=$_POST["slideshow"];}
if(isset($_POST["slideshowtime"])){$slideshowtime=$_POST["slideshowtime"];}




$sql="update components set title='".result($_POST["title"])."',content='".result($_POST["content"])."',arows=".result($_POST["rows"]).",acells=".result($_POST["columns"]).",quantity=".result($_POST["quantity"]).",slideshow=".$slideshow.",slideshowtime=".result($slideshowtime).",types='".result($_POST["types"])."',category=".result($_POST["category"]).",user='".result($_POST["user"])."' where id=".(int)$_GET["id"];
$db->execute($sql);

$db->close();

header("location:index.php");
?>