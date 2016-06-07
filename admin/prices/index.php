<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_prices");

?>
<? include("../inc/begin.php");?>






<h1><?=word_lang("prices")?>:</h1>


<?
$d=1;
if(isset($_GET["d"])){$d=$_GET["d"];}
?>


<div class="tabbable nav-tabs-custom">
    	<ul class="nav nav-tabs" style="margin:10px 6px 10px 6px">
			<li <?if($d==1){echo("class='active'");}?>><a href="index.php?d=1"><?=word_lang("photo")?></a></li>
			<li <?if($d==2){echo("class='active'");}?>><a href="index.php?d=2"><?=word_lang("video")?></a></li>
			<li <?if($d==3){echo("class='active'");}?>><a href="index.php?d=3"><?=word_lang("audio")?></a></li>
			<li <?if($d==4){echo("class='active'");}?>><a href="index.php?d=4"><?=word_lang("vector")?></a></li>
    	</ul>


<div class="box_padding">



<?if($d==1){include("photo.php");}?>
<?if($d==2){include("video.php");}?>
<?if($d==3){include("audio.php");}?>
<?if($d==4){include("vector.php");}?>

</div>

</div>






<? include("../inc/end.php");?>