<?$site="moneyorder";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>
<?include("payments_settings.php");?>

<h1><?=$site_cheque_account?></h1>

<?=translate_text($site_cheque_account2)?>
<br>

<?
include("payments_statement.php");
?>








<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>
