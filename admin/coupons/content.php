<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_coupons");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>


<div class="back"><a href="index.php" class="btn btn-mini"><i class="icon-arrow-left"></i> <?=word_lang("back")?></a></div>

<h1><?=word_lang("Edit")?> &mdash; <?=word_lang("Coupon")?>:</h1>

<div class="box box_padding">

<form method="post" action="change.php?id=<?=(int)$_GET["id"]?>">

<?
$sql="select * from coupons where id_parent=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
?>

<div class='admin_field'>
<span><?=word_lang("code")?>:</span>
<input type='text' name='code' style='width:300px' class='ibox form-control' value='<?=$rs->row["coupon_code"]?>'>
<br><small>if the code is not unique or empty the script will generate a random one.</small>
</div>

<div class='admin_field'>
<span><?=word_lang("title")?>:</span>
<input type='text' name='title' style='width:200px' class='ibox form-control' value='<?=$rs->row["title"]?>'>
</div>


<div class='admin_field'>
<span><?=word_lang("user")?>:</span>
<input type='text' name='user' style='width:200px' class='ibox form-control' value='<?=$rs->row["user"]?>'>
</div>

<div class='admin_field'>
<span><?=word_lang("limit of usage")?>:</span>
<input type='text' name='limit_of_usage' style='width:100px' class='ibox form-control' value='<?=$rs->row["ulimit"]?>'>
</div>

<div class='admin_field'>
<span><?=word_lang("total discount")?>:</span>
<input type='text' name='total' style='width:100px' class='ibox form-control' value='<?=$rs->row["total"]?>'>
</div>


<div class='admin_field'>
<span><?=word_lang("percentage discount")?>:</span>
<input type='text' name='percentage' style='width:100px' class='ibox form-control' value='<?=$rs->row["percentage"]?>'>
</div>


<div class='admin_field'>
<span><?=word_lang("free download")?> URL:</span>
<input type='text' name='url' style='width:300px' class='ibox form-control' value='<?=$rs->row["url"]?>'>
</div>



<div class='admin_field'>
<span><?=word_lang("days till expiration")?>:</span>
<input type='text' name='days' style='width:100px' class='ibox form-control' value='<?=round(($rs->row["data"]-$rs->row["data2"])/86400)?>'>
</div>

<input type='submit' class="btn btn-primary" style="margin-left:6px" value='<?=word_lang("save")?>'>

<?
}
?>


</form>

</div>


<? include("../inc/end.php");?>