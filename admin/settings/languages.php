<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_languages");

?>
<? include("../inc/begin.php");?>


<h1><?=word_lang("languages")?>:</h1>


<p>You can modify the translations here on ftp: <b>/admin/languages/</b></p>

<form method="post" action="language_change.php">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover">
<tr>
<th></th>
<th style="width:80%"><b><?=word_lang("language")?></b></th>
<th><b><?=word_lang("display")?></b></th>
</tr>
<?
$tr=1;
$sql="select * from languages order by name";
$rs->open($sql);
while(!$rs->eof)
{

$lng3=strtolower($rs->row["name"]);
if($lng3=="chinese traditional"){$lng3="chinese";}
if($lng3=="chinese simplified"){$lng3="chinese";}
if($lng3=="afrikaans formal"){$lng3="afrikaans";}
if($lng3=="afrikaans informal"){$lng3="afrikaans";}
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td><input type="radio" name="language" value="<?=$rs->row["name"]?>" <?if($rs->row["activ"]==1){echo("checked");}?>></td>
<td class="big"><img src="../images/languages/<?=$lng3?>.gif" width="18" height="12">&nbsp;<?=$rs->row["name"]?></td>
<td><input type="checkbox" name="<?=str_replace(" ","_",strtolower($rs->row["name"]))?>" value="1" <?if($rs->row["display"]==1){echo("checked");}?>></td>
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
		<div id="button_bottom"><input type="submit" value="<?=word_lang("change")?>" class="btn btn-primary"></div></div>
</form>















<? include("../inc/end.php");?>