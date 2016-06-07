<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("templates_skins");
?>
<? include("../inc/begin.php");?>


<script>
$(document).ready(function(){
	$("#add_new").colorbox({width:"300",height:"230", inline:true, href:"#new_box",scrolling:false});
});
</script>


<a class="btn btn-success toright" id="add_new" href="#"><i class="icon-list icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add")?></a>

<div style='display:none'>
		<div id='new_box'>
			<form method="post" action="template_add.php">
				<div class="admin_field">
					<span><b><?=word_lang("title")?>:</b></span>
					<input type="text" name="name" style="width:200px">
				</div>

				<div class="admin_field">
					<span><b>URL:</b></span>
					<input type="text" name="url" value="templates/template_new/" style="width:200px">
				</div>

				<div class="admin_field">
					<input type="submit" value="<?=word_lang("add")?>" class="btn btn-primary">
				</div>
			</form>
		</div>
</div>


<h1><?=word_lang("select skin")?>:</h1>


<form method="post" action="template_color_change.php">


<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover" style="width:100%">
<tr>

<th><b><?=word_lang("active")?></b></th>
<th><b><?=word_lang("title")?></b></th>
<th><b><?=word_lang("folder")?></b></th>
<th><b><?=word_lang("delete")?></b></th>
</tr>
<?
$sql="select * from templates order by id";
$rs->open($sql);
$tr=1;
while(!$rs->eof)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td><input type="radio" name="activ" value="<?=$rs->row["id"]?>" <?if($rs->row["activ"]==1){echo("checked");}?>></td>
<td><input type="text" name="name<?=$rs->row["id"]?>" value="<?=$rs->row["name"]?>" style="width:150px"></td>
<td><input type="text" name="url<?=$rs->row["id"]?>" value="<?=$rs->row["url"]?>" style="width:200px"></td>
<td><input type="checkbox" name="del<?=$rs->row["id"]?>"></td>
</tr>
<?
$tr++;
$rs->movenext();
}
?>


</table>
</div></div></div></div></div></div></div></div>
<input type="submit" value="<?=word_lang("change")?>" class="btn btn-primary" style="margin:15px 0px 0px 6px">
</form>






<? include("../inc/end.php");?>