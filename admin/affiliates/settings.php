<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("affiliates_settings");
?>
<? include("../inc/begin.php");?>






<h1><?=word_lang("settings")?>:</h1>

<div class="box box_padding">

<form method="post" action="change.php">

<div class="admin_field">
<b>Buyer signup commission:</b><br>
<small>If a buyer signs up then an affiliate gets % commission from all orders of the buyer.</small><br>
<input type="text" name="buyer" style="width:100px;margin-top:3px;" value="<?=$global_settings["buyer_commission"]?>">
</div>

<div class="admin_field">
<b>Seller signup commission:</b><br>
<small>If a seller signs up then an affiliate gets % commission from all sells of the seller.</small><br>
<input type="text" name="seller" style="width:100px;margin-top:3px;" value="<?=$global_settings["seller_commission"]?>">
</div>

<div class="admin_field">
<b>Change the commission rates for the existed affiliates?</b><br>
<select name="addto" style="width:70px">
	<option value="0">No</option>
	<option value="1">Yes</option>
</select>
</div>

<div class="admin_field">
<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>">
</div>
</form>

</div>

<? include("../inc/end.php");?>