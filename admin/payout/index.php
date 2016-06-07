<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_payout");

?>
<? include("../inc/begin.php");?>



<?
if(isset($_GET["d"])){$d=(int)$_GET["d"];}
else{$d=1;}
?>

<h1><?=word_lang("refund")?></h1>


<div class="tabbable nav-tabs-custom">
    	<ul class="nav nav-tabs" style="margin:10px 0px 10px 0px">
    		<li <?if($d==1){echo("class='active'");}?>><a href="index.php?d=1"><?=word_lang("settings")?></a></li>
			<li <?if($d==2){echo("class='active'");}?>><a href="index.php?d=2"><?=word_lang("price")?></a></li>
			<li <?if($d==3){echo("class='active'");}?>><a href="index.php?d=3"><?=word_lang("banks")?></a></li>
    	</ul>





<div class="box_padding">


<?if($d==1){include("settings.php");}?>
<?if($d==2){include("price.php");}?>
<?if($d==3){include("banks.php");}?>



</div>
</div>






<? include("../inc/end.php");?>