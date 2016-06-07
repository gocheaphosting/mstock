<?$site="downloads";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");exit();}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>
<h1><?=word_lang("downloads")?></h1>

<div class="alert"><?=word_lang("daily limit error")?> <b>(<?=$global_settings["daily_download_limit"]?>)</b></div>











<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>