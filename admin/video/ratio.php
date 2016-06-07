<?
//Check access
admin_panel_access("settings_video");

if(!defined("site_root")){exit();}
?>
<form method="post" action="ratio_add.php" style="margin-bottom:20px">
<input name="new" type="text" value="" style="width:200px;display:inline">&nbsp;&nbsp;&nbsp;&nbsp;<input name="width" type="text" value="4" style="width:50px;display:inline">&nbsp;:&nbsp;<input name="height" type="text" value="3" style="width:50px;display:inline">&nbsp;<input type="submit" class="btn btn-success" value="<?=word_lang("add")?>" style="display:inline">
</form>



<?
//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];


//Количество страниц на странице
$kolvo2=k_str2;
?>















<?
$tr=1;
$n=0;
$sql="select * from video_ratio order by name";
$rs->open($sql);
if(!$rs->eof)
{
?>
<form method="post" action="ratio_delete.php">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover">
<tr>
<th colspan="3"><b><?=word_lang("name")?>:</b></th>
<th><b><?=word_lang("delete")?>:</b></th>

</tr>
<?
while(!$rs->eof)
{
if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>



<td><input type="text" name="title<?=$rs->row["id"]?>" value="<?=$rs->row["name"]?>" style="width:250px"></td>

<td><input type="text" name="width<?=$rs->row["id"]?>" value="<?=$rs->row["width"]?>" style="width:50px"></td>

<td><input type="text" name="height<?=$rs->row["id"]?>" value="<?=$rs->row["height"]?>" style="width:50px"></td>


<td><input type="checkbox" id="m<?=$rs->row["id"]?>" name="m<?=$rs->row["id"]?>" value="1"></td>
</tr>

<?
}
$tr++;
$n++;
$rs->movenext();
}
?>
</table>
</div></div></div></div></div></div></div></div>


<input type="submit" class="btn btn-primary" value="<?=word_lang("save")?>" style="margin:10px 0px 20px 6px"></form>

<?
echo(paging($n,$str,$kolvo,$kolvo2,"index.php","&d=2"));
}
else
{
echo("<b>".word_lang("not found")."</b>");
}
?>