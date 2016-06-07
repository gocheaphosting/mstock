<?$site="affiliate";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>










<?
$d=1;
if(isset($_GET["d"])){$d=$_GET["d"];}
if($d==""){$d=1;}
?>



<?include("profile_top.php");?>






<?if($d==1){include("affiliate_balance.php");}?>
<?if($d==2){include("affiliate_earning.php");}?>
<?if($d==3){include("affiliate_refund.php");}?>
<?if($d==4){include("affiliate_settings.php");}?>







<?include("profile_bottom.php");?>


<?include("../inc/footer.php");?>