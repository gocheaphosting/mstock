<?include("../admin/function/db.php");?>
<?
foreach ($_POST as $key => $value)
{
	if(preg_match("/billing/",$key) or preg_match("/shipping/",$key))
	{
		$_SESSION[$key]=$value;
	}
}

$_SESSION["shipping_thesame"]=(int)@$_POST["thesame"];

if(!isset($_POST["thesame"]))
{
	$_POST["thesame"]=1;
}

if((int)$_POST["thesame"]==1)
{
	foreach ($_SESSION as $key => $value)
	{
		if(preg_match("/billing/",$key))
		{
			$_SESSION[str_replace("billing","shipping",$key)]=$_SESSION[$key];
		}
	}
}

$_SESSION["checkout_steps"]=2;

header("location:checkout.php");
?>