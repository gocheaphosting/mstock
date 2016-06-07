<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_catalog");
?>
<? include("../inc/begin.php");?>

<div class="back"><a href="index.php" class="btn btn-mini"><i class="icon-arrow-left"></i>  <?=word_lang("back")?></a></div>

<h1><?=word_lang("filter")?>:</h1>

<p>You can write a list of bad/undesirable words here. The script will search the publication which contains the words.<br> You should use comma "," as separator. </p>

<form method="post" action="filter_change.php">
<?
$sql="select words from content_filter";
$rs->open($sql);
if(!$rs->eof)
{
?>
<div class="form_field">
<textarea name="filter" style="width:400px;height:200px" class="ft"><?=$rs->row["words"]?></textarea></div>
<?
}
?>
<div class="form_field">
<input type="submit" value="<?=word_lang("change")?>" class='btn btn-primary'>
</div>
</form>


<? include("../inc/end.php");?>