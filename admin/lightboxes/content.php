<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("catalog_lightboxes");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>


<div class="back"><a href="index.php" class="btn btn-primary btn-sm btn-mini"><i class="icon-arrow-left fa fa-arrow-left"></i> <?=word_lang("back")?></a></div>

<h1><?=word_lang("edit")?> &mdash; <?=word_lang("lightboxes")?>:</h1>

<div class="box box_padding">
<?
$sql="select * from lightboxes where id=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
?>

<form method="post" action="change.php?id=<?=(int)$_GET["id"]?>">

<div class='admin_field'>
	<span><?=word_lang("title")?>:</span>
	<input type='text' name='title' style='width:400px' class='ibox form-control' value='<?=$rs->row["title"]?>'>
</div>

<div class='admin_field'>
	<span><?=word_lang("show in catalog")?>:</span>
	<input type='checkbox' value='1' name='catalog' <?if($rs->row["catalog"]==1){echo("checked");}?>>
</div>


<input type='submit' value='<?=word_lang("save")?>' class="btn btn-primary" style="margin-left:6px"></form>

<?
}
?>
</div>

<? include("../inc/end.php");?>