<?
//Check access
admin_panel_access("settings_prices");

?>
<?if(!defined("site_root")){exit();}?>


<?
$photo_formats=array();
$sql="select * from photos_formats where enabled=1 order by id";
$rs->open($sql);
while(!$rs->eof)
{
	$photo_formats[]=$rs->row["photo_type"];
	$rs->movenext();
}
?>


<div class="subheader"><?=word_lang("Add new price")?></div>
<div class="subheader_text">


<p>If you want to add an original size of a photo you need to set Max width/height = 0</p>



<form method="post" action="photo_add.php">
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped" style="width:100%">
<tr>
<th><b><?=word_lang("title")?></b></th>
<th><b>Max width/height</b></th>
<th><b><?=word_lang("price")?></b></th>
<th><b><?=word_lang("file types")?></b></th>
<th><b><?=word_lang("priority")?></b></th>
<th><b><?=word_lang("license")?></b></th>
<th><b><?=word_lang("watermark")?></b></th>
<th><b><?=word_lang("settings")?></b></th>
</tr>
<tr>
<td><input type="text" name="title" value="New price" style="width:200px"></td>
<td><input type="text" name="size" value="0" style="width:50px"></td>
<td><input type="text" name="price" value="1.00" style="width:50px"></td>
<td>
<?
foreach ($photo_formats as $key => $value) 
{
	?>
	<div style="margin-bottom:5px"><input type="checkbox" name="<?=$value?>" value="1" checked>&nbsp;<?=$value?></div>
	<?
}
?>
</td>
<td><input type="text" name="priority" value="0" style="width:50px"></td>
<td>
<select name="license" style="width:200px">
<?
$sql="select * from licenses order by priority";
$rs->open($sql);
while(!$rs->eof)
{
?>
<option value="<?=$rs->row["id_parent"]?>"><?=$rs->row["name"]?></option>
<?
$rs->movenext();
}
?>
</select>
</td>
<td>
<input type="checkbox" name="watermark">
</td>
<td>
<select name="addto" style="width:200px">
<option value="0">Add to NEW publications only</option>
<option value="1">Add to ALL  publications</option>
</select>
</td>
</tr>
</table>
</div></div></div></div></div></div></div></div>
<input type="submit" class="btn btn-success" value="<?=word_lang("add")?>" style="margin:10px 0px 0px 6px">
</form>

<?
if(isset($_GET["type"]))
{
?>
<p class="warning">
<?
if($_GET["type"]=="add")
{
?>
<b>The photo size has been added successfully.</b>
<?
}
if($_GET["type"]=="change")
{
?>
<b>The photo sizes have been changed successfully.</b>
<?
}
if(isset($_GET["items_count"]))
{
?>
<br><?=$_GET["items_count"]?> photos were changed.
<?
}
?>
</p>
<?
}
?>
</div>


<div class="subheader"><?=word_lang("prices")?>:</div>
<div class="subheader_text">



<?
$sql="select * from licenses order by priority";
$rs->open($sql);
if(!$rs->eof)
{
?>
<form method="post" action="photo_change.php">
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped " style="width:100%">
<tr>
<th style="width:60%"><b><?=word_lang("title")?></b></th>
<th><b><?=word_lang("file types")?></b></th>
<th><b>Max width/height</b></th>
<th><b><?=word_lang("price")?></b></th>
<th><b><?=word_lang("priority")?></b></th>
<th><b><?=word_lang("watermark")?></b></th>
<th><b><?=word_lang("delete")?></b></th>
</tr>
<?
while(!$rs->eof)
{
?>
<tr class="snd">
<td colspan="7" class="big"><?=$rs->row["name"]?></td>
</tr>
<?
$sql="select * from sizes where license=".$rs->row["id_parent"]." order by priority";
$dr->open($sql);
while(!$dr->eof)
{
?>
<tr>
<td><input type="text" name="title<?=$dr->row["id_parent"]?>" value="<?=$dr->row["title"]?>" style="width:300px"></td>
<td>
<?
foreach ($photo_formats as $key => $value) 
{
	?>
	<div style="margin-bottom:5px"><input type="checkbox" name="<?=$value?><?=$dr->row["id_parent"]?>" value="1" <?if($dr->row[$value]==1){echo("checked");}?>>&nbsp;<?=$value?></div>
	<?
}
?>
</td>
<td><input type="text" name="size<?=$dr->row["id_parent"]?>" value="<?=$dr->row["size"]?>" style="width:50px"></td>
<td><input type="text" name="price<?=$dr->row["id_parent"]?>" value="<?=float_opt($dr->row["price"],2)?>" style="width:50px"></td>
<td><input type="text" name="priority<?=$dr->row["id_parent"]?>" value="<?=$dr->row["priority"]?>" style="width:50px"></td>
<td align="center"><input type="checkbox" name="watermark<?=$dr->row["id_parent"]?>" value="1" <?if($dr->row["watermark"]==1){echo("checked");}?>></td>
<td align="center"><input name="delete<?=$dr->row["id_parent"]?>" type="checkbox"></td>
</tr>
<?
$dr->movenext();
}
$rs->movenext();
}
?>

<tr class="snd">
<td colspan="7">
<select name="addto" style="width:250px">
<option value="0">Not to change OLD publications</option>
<option value="1">Change ALL  publications</option>
</select>
</td>
</tr>

</table>
</div></div></div></div></div></div></div></div>


<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin:10px 0px 0px 6px">
</form><br>
<?
}
?>
</div>


<div class="subheader"><?=word_lang("file types")?>:</div>
<div class="subheader_text">
<a name="formats"></a>

<?
$sql="select * from photos_formats order by id";
$rs->open($sql);
?>
<form method="post" action="photo_formats_change.php">
<?
while(!$rs->eof)
{
	?>
	<div style="margin-bottom:5px"><input type="checkbox" name="<?=$rs->row["photo_type"]?>" value="1" <?if($rs->row["enabled"]==1){echo("checked");}?>>&nbsp;<?=$rs->row["title"]?></div>
	<?
	$rs->movenext();
}
?>



<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>" style="margin:10px 0px 0px 6px">
</form><br>


</div>
