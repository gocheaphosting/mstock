<?$site="signup";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>
<div class="page_internal">
<h1><?=word_lang("sign up")?></h1>

<?if($_GET["activation"]=="off"){?>
<p><?=word_lang("Thank you. You've successfully registered on the site. But your account is disabled for now")?></p>
<?}?>

<?if($_GET["activation"]=="on"){?>
<p><?=word_lang("Thank you. You've successfully registered on the site.")?></p>
<?}?>

<?if($_GET["activation"]=="user"){?>
<p><?=word_lang("confirmation sent")?></p>
<?}?>

<?if($_GET["activation"]=="admin"){?>
<p><?=word_lang("Thank you. You've successfully registered on the site. Your account will be activated by admin.")?></p>
<?}?>




</div>
<?include("../inc/footer.php");?>