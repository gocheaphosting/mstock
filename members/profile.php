<?$site="profile";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>



<?
if($_SESSION["people_type"]=="")
{
?>
<?include("../inc/header.php");?>
<h1><?=word_lang("member area")?></h1>

<p>You logged in for the first time. You should select who you are:</p>

<form method="post" action="profile_type_change.php">

<p><input type="radio" name="utype" value="buyer" checked> - <b><?=word_lang("buyer")?></b></p>

<?if($global_settings["userupload"]==1){?>
<p><input type="radio" name="utype" value="seller"> - <b><?=word_lang("seller")?></b></p>
<?}?>

<?if($global_settings["affiliates"]){?>
<p><input type="radio" name="utype" value="affiliate"> - <b><?=word_lang("affiliate")?></b></p>
<?}?>

<input type="submit" class="isubmit" value="Ok">
</form>

<?include("../inc/footer.php");?>
<?
}

?>















