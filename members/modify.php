<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");exit();}?>
<?



$flag=true;

//Проверяем есть ли такой пользователь
$sql="select * from users where login='".result($_POST["login"])."'";
echo($sql);
$rs->open($sql);
if(!$rs->eof and $_POST["login"]!=$_SESSION["people_login"])
{
	$flag=false;
	header("location:profile_about.php?d=1");
	exit();
}

//Проверяем есть ли такой e-mail
$sql="select * from users where email='".result($_POST["email"])."'";
$rs->open($sql);
if(!$rs->eof  and $_POST["email"]!=$_SESSION["people_email"])
{
	$flag=false;
	header("location:profile_about.php?d=2");
	exit();
}





if($flag==true and !$demo_mode)
{
	if(isset($_POST["newsletter"])){$newsletter=1;}
	else{$newsletter=0;}
	
	$sql="select id_parent,country from users where login='".result($_POST["login"])."'";
	$rs->open($sql);
	if(!$rs->eof)
	{
		$sql="update users set login='".result(@$_POST["login"])."',password='".md5(result(@$_POST["password"]))."',email='".result(@$_POST["email"])."',name='".result(@$_POST["name"])."',telephone='".result(@$_POST["telephone"])."',address='".result(@$_POST["address"])."',country='".result(@$_POST["country"])."',city='".result(@$_POST["city"])."',state='".result(@$_POST["state"])."',zipcode='".result(@$_POST["zipcode"])."',lastname='".result(@$_POST["lastname"])."',description='".result(@$_POST["description"])."',website='".result(@$_POST["website"])."',utype='".result(@$_POST["utype"])."',company='".result(@$_POST["company"])."',newsletter=".$newsletter.",business=".(int)@$_POST["business"].",vat='".result(@$_POST["vat"])."'  where id_parent=".(int)$_SESSION["people_id"];	
		$db->execute($sql);
		$_SESSION["people_name"]=result(@$_POST["name"]);
		$_SESSION["people_email"]=result(@$_POST["email"]);
		
		if($rs->row["country"] != result(@$_POST["country"]))
		{
			$sql="update users set country_checked=0,country_checked_date=0 where id_parent=".$rs->row["id_parent"];
			$db->execute($sql);
		}
	}
	header("location:profile_about.php?d=3");
}



$db->close();

?>