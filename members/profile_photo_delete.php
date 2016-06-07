<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>


<?

$sql="select ".result3($_GET["type"]).",id_parent from users where id_parent=".(int)$_SESSION["people_id"];
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row[result3($_GET["type"])]!="")
	{
		unlink($_SERVER["DOCUMENT_ROOT"].$rs->row[result3($_GET["type"])]);
	}
}

$sql="update users set ".result3($_GET["type"])."='' where id_parent=".(int)$_SESSION["people_id"];
$db->execute($sql);



$db->close();

Header("location:profile_about.php");
?>





