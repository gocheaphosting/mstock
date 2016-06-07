<?$site="commission";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>
<?include("../inc/header.php");?>










<?
if(isset($_GET["d"])){$d=(int)$_GET["d"];}
else{$d=1;}
?>



<?include("profile_top.php");?>






<?if($d==1){include("commission_balance.php");}?>
<?if($d==2){include("commission_earning.php");}?>
<?if($d==3){include("commission_refund.php");}?>
<?if($d==4){include("commission_settings.php");}?>







<?include("profile_bottom.php");?>


<?include("../inc/footer.php");?>