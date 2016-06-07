<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("users_password");



$sql="select * from people where id=".$_SESSION["user_id"]." and password='".md5(result($_POST["p0"]))."'";
$rs->open($sql);
if(!$rs->eof)
{
	if($_POST["p1"]!=$_POST["p2"])
	{
		header("location:password.php?d=2");
	}
	else
	{
		if(!$demo_mode)
		{
			$sql="update people set password='".md5(result($_POST["p1"]))."' where id=".$_SESSION["user_id"];
			$db->execute($sql);
		}
		
		header("location:password.php?d=1");
	}
}
else
{
	header("location:password.php?d=3");
}

$db->close();
?>