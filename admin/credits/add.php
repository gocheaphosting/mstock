<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_credits");


$sql="select login from users where login='".result($_POST["user"])."'";
$dd->open($sql);
if(!$dd->eof)
{
	if((float)$_POST["quantity"]>0)
	{
		$expiration_date=0;
		if((int)$_POST["days"]>0)
		{
			$expiration_date=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))+3600*24*$_POST["days"];
		}
		
		$sql="insert into credits_list (title,data,user,quantity,approved,payment,credits,expiration_date) values ('".result($_POST["title"])."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".$dd->row["login"]."',".(float)$_POST["quantity"].",1,0,0,".$expiration_date.")";
		$db->execute($sql);
	}
	
	header("location:index.php");
}
else
{
	header("location:new.php?d=1");
}

$db->close();
?>
