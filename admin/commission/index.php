<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_commission");

?>
<? include("../inc/begin.php");?>





<h1><?=word_lang("commission manager")?>:</h1>


<?
if(isset($_GET["d"])){$d=$_GET["d"];}
else{$d=1;}
?>



<div class="tabbable nav-tabs-custom">
    	<ul class="nav nav-tabs" style="margin:10px 6px 10px 6px">
    		<li <?if($d==1){echo("class='active'");}?>><a href="index.php?d=1"><?=word_lang("commission")?></a></li>
    		<li <?if($d==2){echo("class='active'");}?>><a href="index.php?d=2"><?=word_lang("refund")?></a></li>
    		<li <?if($d==3){echo("class='active'");}?>><a href="index.php?d=3"><?=word_lang("users earnings")?></a></li>
    	</ul>
<div class="box_padding">



<?if($d==1){include("commission.php");}?>
<?if($d==2){include("refund.php");}?>
<?if($d==3){include("balance.php");}?>

</div>
</div>








<? include("../inc/end.php");?>