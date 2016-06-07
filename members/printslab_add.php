<?
include("../admin/function/db.php");
if(!isset($_SESSION["people_id"])){header("location:login.php");}

$sql="insert into galleries (user_id,title,data) values (".(int)$_SESSION["people_id"].",'".result($_POST["title"])."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).")";
$db->execute($sql);

header("location:printslab.php");
?>

