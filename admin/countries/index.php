<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_countries");

?>
<? include("../inc/begin.php");?>

<script language="javascript">
function select_all_countries()
{
	$('#form_countries input:checkbox').each(function(){this.checked = !this.checked;});
}

function select_all_eucountries()
{
	$("input:checkbox").attr("checked",false);
	$('input.eu:checkbox').prop('checked','checked');
}
</script>

<h1><?=word_lang("Countries")?>:</h1>
<p>

<form method="post" action="add.php" style="margin-bottom:20px;float:right">
<input name="country" type="text" value="" style="width:200px;display:inline">&nbsp;<input type="submit" class="btn btn-success" value="<?=word_lang("add")?>" style="display:inline">
</form>

<a href="javascript:select_all_countries()" class="btn btn-default btn-sm"><i class="fa fa-check-square"></i>&nbsp; 
 <?=word_lang("select all")?>/<?=word_lang("deselect all")?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="javascript:select_all_eucountries()" class="btn btn-default btn-sm"><i class="fa fa-check-circle"></i>&nbsp; <?=word_lang("Select EU Countries")?></a>
</p><br>



<form method="post" action="change.php" id="form_countries">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover">
<tr>
<th><b><?=word_lang("active")?></b></th>
<th style="width:80%"><b><?=word_lang("country")?></b></th>
<th><b><?=word_lang("priority")?></b></th>
<th></th>
</tr>
<?
$tr=1;
$sql="select * from countries order by priority,name";
$rs->open($sql);
while(!$rs->eof)
{
$eu="";
if(in_array($rs->row["name"], $mcountry_eu))
{
	$eu="class='eu'";
}
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td><input type="checkbox" name="country<?=$rs->row["id"]?>" <?if($rs->row["activ"]==1){echo("checked");}?> value="1" <?=$eu?>></td>
<td class="big"><?=$rs->row["name"]?></td>
<td><input type="text" name="priority<?=$rs->row["id"]?>" value="<?=$rs->row["priority"]?>"></td>
<td><div class="link_delete"><a href='delete.php?id=<?=$rs->row["id"]?>'><?=word_lang("delete")?></a></div></td>
</tr>
<?
$tr++;
$rs->movenext();
}
?>


</table>
</div></div></div></div></div></div></div></div>


<div id="button_bottom_static">
		<div id="button_bottom_layout"></div>
		<div id="button_bottom"><input type="submit" value="<?=word_lang("save")?>" class="btn btn-primary"></div></div>
</form>















<? include("../inc/end.php");?>