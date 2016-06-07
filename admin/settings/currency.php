<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_currency");

?>
<? include("../inc/begin.php");?>


<script>
$(document).ready(function(){
	$("#currency_add").colorbox({width:"300",height:"280", inline:true, href:"#new_box",scrolling:false});
});
</script>


<a class="btn btn-success toright" href="content.php" id="currency_add"><i class="icon-plus icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add")?></a>

<div style='display:none'>
		
		<div id='new_box'>
		<div class="modal_header"><?=word_lang("currency")?></div>
		
		<form method="post" action="currency_add.php" style="margin:0px">
			<div class="form_field">
				<span><b><?=word_lang("name")?></b></span>
				<input type="text" name="name" value="" style="width:143px">
			</div>
			<table border="0" cellpadding="0" cellspacing="0">	
			<tr>
			<td>
			<div class="form_field">
				<span><b><?=word_lang("code")?></b></span>
				<input type="text" name="code" value="" style="width:60px">
			</div>
			</td>
			<td style="padding-left:10px">
			<div class="form_field">
				<span><b><?=word_lang("symbol")?></b></span>
				<input type="text" name="symbol" value="" style="width:60px">
			</div>
			</td>
			</tr>
			</table>

				<input class="btn btn-primary" type="submit" value="<?=word_lang("add")?>">
		</form>
		</div>
</div>

<h1><?=word_lang("currency")?>:</h1>

<form method="post" action="currency_change.php">

<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border=0 cellpadding=5 cellspacing=1 class="table_admin table table-striped table-hover">
<tr>
<th></th>
<th><b><?=word_lang("name")?></b></th>
<th><b><?=word_lang("code")?></b></th>
<th><b><?=word_lang("symbol")?></b></th>
<th></th>
</tr>

<?
$tr=1;
$sql="select * from currency order by activ desc,name";
$rs->open($sql);
while(!$rs->eof)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td><input type="radio" name="currency" value="<?=$rs->row["code1"]?>" <?if($rs->row["activ"]==1){echo("checked");}?>></td>
<td><span class="big"><?=$rs->row["name"]?></span></td>
<td><span class="gray"><?=$rs->row["code1"]?></span></td>
<td><span class="gray"><?=$rs->row["code2"]?></span></td>
<td>
<?if($rs->row["activ"]!=1){?>
<div class="link_delete"><a href="currency_delete.php?id=<?=$rs->row["id"]?>" onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div>
<?}?>
</td>
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
		<div id="button_bottom">
			<input type="submit" value="<?=word_lang("change")?>" class="btn btn-primary" style="margin-top:20px">
		</div>
	</div>

</form>









<? include("../inc/end.php");?>