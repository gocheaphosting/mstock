<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_stockapi");

?>
<? include("../inc/begin.php");?>


<h1><?=word_lang("Stock API")?>:</h1>

<?
$d=0;
if(isset($_GET["d"])){$d=$_GET["d"];}
?>



<div class="tabbable nav-tabs-custom">
    	<ul class="nav nav-tabs" style="margin:10px 0px 10px 0px">
    		<li <?if($d==0){echo("class='active'");}?>><a href="index.php?d=0"><?=word_lang("settings")?></a></li>
    		<li <?if($d==1){echo("class='active'");}?>><a href="index.php?d=1">Getty/iStock</a></li>
			<li <?if($d==2){echo("class='active'");}?>><a href="index.php?d=2">Shutterstock</a></li>
			<li <?if($d==3){echo("class='active'");}?>><a href="index.php?d=3">Fotolia</a></li>
			<li <?if($d==4){echo("class='active'");}?>><a href="index.php?d=4">Depositphotos</a></li>
			<li <?if($d==5){echo("class='active'");}?>><a href="index.php?d=5">123rf</a></li>
			<li <?if($d==6){echo("class='active'");}?>><a href="index.php?d=6">Bigstockphoto</a></li>
    	</ul>



<div class="box_padding">


<?if($d==0){include("settings.php");}?>
<?if($d==1){include("istockphoto.php");}?>
<?if($d==2){include("shutterstock.php");}?>
<?if($d==3){include("fotolia.php");}?>
<?if($d==4){include("depositphotos.php");}?>
<?if($d==5){include("123rf.php");}?>
<?if($d==6){include("bigstockphoto.php");}?>

</div>

</div>






<? include("../inc/end.php");?>