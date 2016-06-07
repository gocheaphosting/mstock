<?$site="login";
include("../admin/function/db.php");?>
<?if(isset($_SESSION["people_id"])){header("location:".site_root."/");}?>
<?include("../inc/header.php");?>

<h1><?=word_lang("member area")?></h1>


<?
if(isset($_GET["d"]))
{
	if($_GET["d"]==1)
	{
		echo("Error. The login is incorrect.");
	}
	if($_GET["d"]==2)
	{
		echo("Error. You failed to login several times. The new password was generated and sent to your email.");
	}
	if($_GET["d"]==3)
	{
		echo("Error. The IP was blocked");
	}
}
?>

<?include("login_content.php");?>


<?include("../inc/footer.php");?>