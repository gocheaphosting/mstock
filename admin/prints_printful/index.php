<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_printful");
?>
<? include("../inc/begin.php");?>






<h1><?=word_lang("Printful prints service")?></h1>


<?
if(isset($_GET["d"]))
{
	$d=(int)$_GET["d"];
}
else
{
	$d=1;
}
?>


<div class="tabbable nav-tabs-custom">
    	<ul class="nav nav-tabs" style="margin:10px 0px 10px 0px">
    		<li <?if($d==1){echo("class='active'");}?>><a href="index.php?d=1"><?=word_lang("settings")?></a></li>
			<li <?if($d==2){echo("class='active'");}?>><a href="index.php?d=2"><?=word_lang("orders")?></a></li>
			<li <?if($d==3){echo("class='active'");}?>><a href="index.php?d=3">Cron</a></li>
    	</ul>

<div class="box_padding">




<?if($d==1){include("settings.php");}?>
<?if($d==2){include("order.php");}?>
<?if($d==3){include("cron.php");}?>

</div>
</div>








<? include("../inc/end.php");?>