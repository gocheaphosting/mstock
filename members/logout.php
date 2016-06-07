<?include("../admin/function/db.php");?>
<?
session_destroy();

setcookie("cookie_login","",time()+60*60*24*30,"/",str_replace("http://","",surl));
setcookie("cookie_password","",time()+60*60*24*30,"/",str_replace("http://","",surl));

$db->close();
header("location:".site_root."/index.php");
?>