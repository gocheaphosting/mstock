<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?



$sql="delete from friends where friend1='".result($_SESSION["people_login"])."' and friend2='".result3($_GET["user"])."'";
$db->execute($sql);

$db->close();

header("location:friends.php");
?>