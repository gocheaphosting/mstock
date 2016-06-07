<?include("../admin/function/db.php");?>
<?
$_SESSION["slang"]=preg_replace('/[^a-z ]/i',"",$_GET["lang"]);
setcookie("cookie_lang",$_SESSION["slang"],time()+60*60*24*30,"/",str_replace("http://","",surl));

unset($_SESSION["box_shopping_cart"]);
unset($_SESSION["box_shopping_cart_lite"]);
unset($_SESSION["site_info_content"]);

$db->close();

if(isset($_SERVER["HTTP_REFERER"]))
{
	header("location:".$_SERVER["HTTP_REFERER"]);
}
else
{
	header("location:".site_root."/");
}
?>