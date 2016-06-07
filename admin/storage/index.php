<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_storage");

?>
<? include("../inc/begin.php");?>


<h1><?=word_lang("file storage")?>:</h1>

<?
$d=1;
if(isset($_GET["d"])){$d=$_GET["d"];}
?>



<div class="tabbable nav-tabs-custom">
    	<ul class="nav nav-tabs" style="margin:10px 0px 10px 0px">
    		<li <?if($d==1){echo("class='active'");}?>><a href="index.php?d=1"><?=word_lang("stats")?></a></li>
			<li <?if($d==2){echo("class='active'");}?>><a href="index.php?d=2"><?=word_lang("local server")?></a></li>
			<li <?if($d==3){echo("class='active'");}?>><a href="index.php?d=3">Rackspace clouds</a></li>
			<li <?if($d==4){echo("class='active'");}?>><a href="index.php?d=4">Amazon S3</a></li>
			<li <?if($d==5){echo("class='active'");}?>><a href="index.php?d=5">Cron job</a></li>
    	</ul>



<div class="box_padding">



<?if($d==1){include("settings.php");}?>
<?if($d==2){include("local.php");}?>
<?if($d==3){include("rackspace.php");}?>
<?if($d==4){include("amazon.php");}?>
<?if($d==5){include("cron.php");}?>

</div>

</div>






<? include("../inc/end.php");?>