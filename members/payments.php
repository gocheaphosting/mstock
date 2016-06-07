<?
$site="profile";
include("../admin/function/db.php");
if(!isset($_SESSION["people_id"])){header("location:login.php");}
include("../inc/header.php");
include("profile_top.php");
include("payments_settings.php");
?>

<input style="float:right" type="button" class="isubmit" value="<?=word_lang("print version")?>" onClick="location.href='<?=site_root?>/members/print_version.php?product_id=<?=$_GET["product_id"]?>&product_type=<?=$_GET["product_type"]?>'">



<a href="
<?
if($_GET["product_type"]=="credits")
{
	echo("credits.php");
}
if($_GET["product_type"]=="subscription")
{
	echo("subscription.php");
}
if($_GET["product_type"]=="order")
{
	echo("orders.php");
}
?>
">&#171; <?=word_lang("back")?></a>



<?
include("payments_statement.php");
?>







<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>