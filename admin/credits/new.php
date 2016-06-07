<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_credits");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>


<div class="back"><a href="index.php" class="btn btn-mini"><i class="icon-arrow-left"></i> <?=word_lang("back")?></a></div>

<h1><?=word_lang("Add")?> <?=word_lang("Credits")?>:</h1>


<?
if(isset($_GET["d"]))
{
echo("<p><b>".word_lang("error")."</b></p>");
}
?>
<div class="box box_padding">
<form method="post" action="add.php">

<div class='admin_field'>
<span><?=word_lang("title")?>:</span>
<input type='text' name='title' style='width:400px' class='ibox form-control' value='Credits bonus'>
</div>
<div class='admin_field'>
<span><?=word_lang("user")?>:</span>
<input type='text' name='user' style='width:400px' class='ibox form-control' value=''>
</div>

<div class='admin_field'>
<span><?=word_lang("quantity")?>:</span>
<input type='text' name='quantity' style='width:100px' class='ibox form-control' value='1'>
</div>



<div class='admin_field'>
<span><?=word_lang("days till expiration")?>:</span>
<input type='text' name='days' style='width:100px' class='ibox form-control' value='0'>
</div>

<input type='submit' value='<?=word_lang("add")?>' class="btn btn-primary" style="margin-left:6px"></form>
<br>
<p>* You should set <b>"<?=word_lang("days till expiration")?>"=0</b> if you don't want to have the expiration date.</p>
</div>

<? include("../inc/end.php");?>