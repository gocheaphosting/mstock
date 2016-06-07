<?
//Check access
admin_panel_access("settings_prices");

?>
<?if(!defined("site_root")){exit();}?>



<div class="subheader"><?=word_lang("Add new price")?></div>
<div class="subheader_text">



<p>* You may add ANY audio type. You should use "," as separator. Example types: mp3,midi,wma</p>

<p>** Sometimes a file's price depends on a license. To avoid uploading the same file for the different licenses you should set one number > 0 in the fields. For example, you set "4" for price A and "4" for price B. A seller will upload only one file and it will be related with price A and price B. The file type of the prices must be the same. If the prices are not related you should set 0.</p>


<form method="post" action="audio_add.php">
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped" style="width:100%">
<tr>
<th><b><?=word_lang("title")?></b></th>
<th><b>*<?=word_lang("type")?></b></th>
<th><b><?=word_lang("shipped")?></b></th>
<th><b><?=word_lang("price")?></b></th>
<th><b><?=word_lang("priority")?></b></th>
<th><b>**<?=word_lang("the same file")?></b></th>
<th><b><?=word_lang("license")?></b></th>
<th><b><?=word_lang("settings")?></b></th>
</tr>
<tr>
<td><input type="text" name="title" value="New price" style="width:100px"></td>
<td><input type="text" name="types" value="mp3" style="width:100px"></td>
<td align="center"><input type="checkbox" name="shipped"></td>
<td><input type="text" name="price" value="1.00" style="width:50px"></td>
<td><input type="text" name="priority" value="0" style="width:50px"></td>
<td><input type="text" name="thesame" value="0" style="width:50px"></td>
<td>
<select name="license" style="width:150px">
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
<select name="addto" style="width:250px">
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
<div class="alert">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<?
if($_GET["type"]=="add")
{
?>
<b>The audio type has been added successfully.</b>
<?
}
if($_GET["type"]=="change")
{
?>
<b>The audio types have been changed successfully.</b>
<?
}
if(isset($_GET["items_count"]))
{
?>
<br><?=$_GET["items_count"]?> audios were changed.
<?
}
?>
</div>
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
<form method="post" action="audio_change.php" style="margin-top:25px">
<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped" style="width:100%">
<tr>
<th style="width:60%"><b><?=word_lang("title")?></b></th>
<th><b>*<?=word_lang("type")?></b></th>
<th><b><?=word_lang("price")?></b></th>
<th><b><?=word_lang("priority")?></b></th>
<th><b>**<?=word_lang("the same file")?></b></th>
<th><b><?=word_lang("shipped")?></b></th>
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
$sql="select * from audio_types where license=".$rs->row["id_parent"]." order by priority";
$dr->open($sql);
while(!$dr->eof)
{
?>
<tr>
<td><input type="text" name="title<?=$dr->row["id_parent"]?>" value="<?=$dr->row["title"]?>" style="width:200px"></td>
<td class="gray">
<?if($dr->row["shipped"]!=1){?>
<input type="text" name="types<?=$dr->row["id_parent"]?>" value="<?=$dr->row["types"]?>" style="width:50px">
<?}else{echo(word_lang("shipped"));
?>
<input type="hidden" name="types<?=$dr->row["id_parent"]?>" value="shipped">
<?}?>
</td>
<td><input type="text" name="price<?=$dr->row["id_parent"]?>" value="<?=float_opt($dr->row["price"],2)?>" style="width:50px"></td>
<td><input type="text" name="priority<?=$dr->row["id_parent"]?>" value="<?=$dr->row["priority"]?>" style="width:50px"></td>
<td><input type="text" name="thesame<?=$dr->row["id_parent"]?>" value="<?=$dr->row["thesame"]?>" style="width:50px"></td>
<td align="center"><input name="shipped<?=$dr->row["id_parent"]?>" type="checkbox" <?if($dr->row["shipped"]==1){echo("checked");}?>></td>
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