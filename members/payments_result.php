<?$site="payments_thanks";?>
<?include("../admin/function/db.php");?>
<?include("../inc/header.php");?>
<div class="page_internal">
<h1><?=word_lang("payment")?></h1>


<?if(isset($_GET["d"]) and $_GET["d"]==1){?>
<p>Thank you! Your transaction has been sent successfully.</p>
<?}?>

<?if(isset($_GET["d"]) and $_GET["d"]==2){?>
<p>Error. The transaction has been declined!</p>
<?}?>

<?if(isset($_GET["d"]) and $_GET["d"]==3){?>
<p>Thank you!. The transaction has 'pending' status. We will let you know when it is approved.</p>
<?}?>

<?
if(isset($_GET["errorCode"]))
{
	echo("<p><b>Error code:</b> ".result($_GET["errorCode"])."</p>");
}
if(isset($_GET["errorName"]))
{
	echo("<p><b>Error Name:</b> ".result($_GET["errorName"])."</p>");
}
if(isset($_GET["errorMsg"]))
{
	echo("<p><b>Error message:</b> ".result($_GET["errorMsg"])."</p>");
}
?>


</div>
<?include("../inc/footer.php");?>