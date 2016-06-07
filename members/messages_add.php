<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>

<?

	//Send to "to"
	$sql="select a.login,b.friend1,b.friend2 from users a,friends b where a.login=b.friend2 and b.friend1='".result($_SESSION["people_login"])."' and b.friend2='".result3($_POST["to"])."'";
	$ds->open($sql);
	if(!$ds->eof)
	{
		$sql="insert into messages (touser,fromuser,subject,content,data,viewed,trash,del) values ('".result($_POST["to"])."','".result($_SESSION["people_login"])."','".result($_POST["subject"])."','".result($_POST["content"])."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",0,0,0)";
		$db->execute($sql);
	}

	//Send to "cc"
	if($_POST["cc"]!="")
	{
		$sql="select a.login,b.friend1,b.friend2 from users a,friends b where a.login=b.friend2 and b.friend1='".result($_SESSION["people_login"])."' and b.friend2='".result3($_POST["cc"])."'";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$sql="insert into messages (touser,fromuser,subject,content,data,viewed,trash,del) values ('".result($_POST["cc"])."','".result($_SESSION["people_login"])."','".result($_POST["subject"])."','".result($_POST["content"])."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",0,0,0)";
			$db->execute($sql);
		}
	}

	//Send to "bcc"
	if($_POST["bcc"]!="")
	{
		$sql="select a.login,b.friend1,b.friend2 from users a,friends b where a.login=b.friend2 and b.friend1='".result($_SESSION["people_login"])."' and b.friend2='".result3($_POST["bcc"])."'";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$sql="insert into messages (touser,fromuser,subject,content,data,viewed,trash,del) values ('".result($_POST["bcc"])."','".result($_SESSION["people_login"])."','".result($_POST["subject"])."','".result($_POST["content"])."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",0,0,0)";
			$db->execute($sql);
		}
	}
	
	$db->close();
	header("location:messages_new.php?d=1");

?>