<?$site="credits";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>
<?include("payments_settings.php");?>

<?
$d=2;
if(isset($_GET["d"])){$d=$_GET["d"];}
if($d==""){$d=2;}
?>



<h1>
<?
if($d==2){echo(word_lang("Credits")." &mdash; ".word_lang("balance"));}
if($d==1){echo(word_lang("buy credits"));}
?>
</h1>

<?
if($d==1)
{
	if($site_fortumo_account!="")
	{
		if(!isset($_GET["type"]))
		{
			include("credits_select.php");
		}
		else
		{
			if($_GET["type"]=="mobile")
			{
				include("credits_mobile.php");
			}
			else
			{
				include("credits_buy.php");
			}
		}
	}
	else
	{
		include("credits_buy.php");
	}
}
if($d==2)
{
	include("credits_list.php");
}
?>
<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>