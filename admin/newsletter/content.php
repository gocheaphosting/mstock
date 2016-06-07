<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("pages_news");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>




<div class="back"><a href="index.php"><b>&#171; <?=word_lang("back")?></b></a></div>


<h1>
<?
echo(word_lang("edit")." &mdash; ".word_lang("news"));
?>
:</h1>



<? include("../inc/end.php");?>