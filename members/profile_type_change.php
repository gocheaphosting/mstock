<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?

if($_SESSION["people_type"]=="")
{
	$sql="update users set utype='".result($_POST["utype"])."' where id_parent=".(int)$_SESSION["people_id"];
	$db->execute($sql);
	$_SESSION["people_type"]=result($_POST["utype"]);
	if($_SESSION["people_type"]=="buyer" or $_SESSION["people_type"]=="seller")
	{
		if(isset($_COOKIE["aff"]))
		{
			affiliate_add((int)$_COOKIE["aff"],(int)$_SESSION["people_id"],$_SESSION["people_type"]);
		}
	}
}

$db->close();

if(isset($_SESSION["redirect_url"]) and $_SESSION["redirect_url"]=="checkout")
{
	header("location:checkout.php");
	exit();
}
else
{
	header("location:profile_home.php");
	exit();
}

?>