<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_networks");
?>
<? include("../inc/begin.php");?>

<h1>Social Networks</h1>


<?
$d=1;
if(isset($_GET["d"])){$d=$_GET["d"];}
?>

<div class="tabbable nav-tabs-custom">
    	<ul class="nav nav-tabs" style="margin:10px 0px 10px 0px">
			<li <?if($d==1){echo("class='active'");}?>><a href="index.php?d=1">Facebook</a></li>
			<li <?if($d==2){echo("class='active'");}?>><a href="index.php?d=2">Twitter</a></li>
			<li <?if($d==4){echo("class='active'");}?>><a href="index.php?d=4">Instagram</a></li>
			<li <?if($d==3){echo("class='active'");}?>><a href="index.php?d=3">Vkontakte</a></li>
    	</ul>


<div class="box_padding">



<?if($d==1){include("facebook.php");}?>
<?if($d==2){include("twitter.php");}?>
<?if($d==4){include("instagram.php");}?>
<?if($d==3){include("vk.php");}?>

</div>
</div>

<? include("../inc/end.php");?>