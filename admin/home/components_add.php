<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("templates_home");

$slideshow=0;
$slideshowtime=1;
if(isset($_POST["slideshow"])){$slideshow=$_POST["slideshow"];}
if(isset($_POST["slideshowtime"])){$slideshowtime=$_POST["slideshowtime"];}


$sql="insert into components (title,content,arows,acells,quantity,slideshow,slideshowtime,types,category,user) values ('".result($_POST["title"])."','".result($_POST["content"])."',".result($_POST["rows"]).",".result($_POST["columns"]).",".result($_POST["quantity"]).",".$slideshow.",".result($slideshowtime).",'".result($_POST["types"])."',".result($_POST["category"]).",'".result($_POST["user"])."')";
$db->execute($sql);

$db->close();

header("location:index.php");
?>