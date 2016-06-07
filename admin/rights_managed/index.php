<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_rightsmanaged");
?>
<? include("../inc/begin.php");?>






<h1><?=word_lang("rights managed files")?></h1>


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


<div class="nav-tabs-custom tabbable">
    	<ul class="nav nav-tabs" style="margin:10px 0px 10px 0px">
    		<li <?if($d==1){echo("class='active'");}?>><a href="index.php?d=1"><?=word_lang("prices")?></a></li>
			<li <?if($d==2){echo("class='active'");}?>><a href="index.php?d=2"><?=word_lang("groups")?></a></li>
    	</ul>

<div class="box_padding">




<?if($d==1){include("prices.php");}?>
<?if($d==2){include("groups.php");}?>

</div>
</div>









<? include("../inc/end.php");?>