<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_audio");
?>
<? include("../inc/begin.php");?>







<h1><?=word_lang("audio settings")?></h1>






<?
$d=0;
if(isset($_GET["d"])){$d=$_GET["d"];}
?>


<div class="tabbable nav-tabs-custom">
    	<ul class="nav nav-tabs" style="margin:10px 6px 10px 6px">
    		<li <?if($d==0){echo("class='active'");}?>><a href="index.php?d=0"><?=word_lang("upload form")?></a></li>
			<li <?if($d==1){echo("class='active'");}?>><a href="index.php?d=1"><?=word_lang("track source")?></a></li>
			<li <?if($d==2){echo("class='active'");}?>><a href="index.php?d=2"><?=word_lang("track format")?></a></li>
    	</ul>


<div class="box_padding">


<?if($d==0){include("fields.php");}?>
<?if($d==1){include("source.php");}?>
<?if($d==2){include("format.php");}?>

</div>



</div>









<? include("../inc/end.php");?>