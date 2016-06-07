<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_bulkupload");





?>
<? include("../inc/begin.php");?>
<? include("../function/upload.php");?>





<h1><?=word_lang("bulk upload")?>:</h1>


<?
if(isset($_GET["d"])){$d=(int)$_GET["d"];}
else{$d=1;}



if(isset($_GET["quantity"]))
{
	$_SESSION["bulk_page"]=(int)$_GET["quantity"];
}


if(!isset($_SESSION["bulk_page"]))
{
	$_SESSION["bulk_page"]=10;
}

if(isset($_GET["str"]))
{
	$str=(int)$_GET["str"];
}
else
{
	$str=1;
}
?>



<div class="tabbable nav-tabs-custom">
    	<ul class="nav nav-tabs" style="margin:10px 0px 10px 0px">
    		<li <?if($d==1){echo("class='active'");}?>><a href="index.php?d=1"><?=word_lang("ftp photo uploader")?></a></li>
			<li <?if($d==5 or $d==6){echo("class='active'");}?>><a href="index.php?d=5"><?=word_lang("java photo uploader")?></a></li>
			<li <?if($d==2){echo("class='active'");}?>><a href="index.php?d=2"><?=word_lang("ftp video uploader")?></a></li>
			<li <?if($d==3){echo("class='active'");}?>><a href="index.php?d=3"><?=word_lang("ftp audio uploader")?></a></li>
			<li <?if($d==4){echo("class='active'");}?>><a href="index.php?d=4"><?=word_lang("ftp vector uploader")?></a></li>
    	</ul>




<div class="box_padding">



<?if($d==1){include("photo.php");}?>
<?if($d==5){include("photo_java.php");}?>
<?if($d==6){include("photo_java2.php");}?>
<?if($d==2){include("video.php");}?>
<?if($d==3){include("audio.php");}?>
<?if($d==4){include("vector2.php");}?>


</div>

</div>


<? include("../inc/end.php");?>