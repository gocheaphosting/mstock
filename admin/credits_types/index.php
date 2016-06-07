<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_creditstypes");
?>
<? include("../inc/begin.php");?>




<script>
$(document).ready(function(){
	$("#add_new").colorbox({width:"970",height:"", inline:true, href:"#new_box",scrolling:false});
});
</script>


<a id="add_new" class="btn btn-success toright" href="#"><i class="icon-tags icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add")?></a>


<h1><?=word_lang("credits types")?>:</h1>


<p>The Credits can have an <b>expiration date</b>. If you don't want to have the expiration you should set <b>"<?=word_lang("days till expiration")?>" = 0</b></p>

<br>




<?
$tr=1;
$sql="select id_parent,title,quantity,price,priority,days from credits order by priority";
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<form method="post" action="change.php">
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("priority")?>:</b></th>
	<th><b><?=word_lang("title")?>:</b></th>
	<th><b><?=word_lang("quantity")?>:</b></th>
	<th><b><?=word_lang("price")?>:</b></th>
	<th><b><?=word_lang("days till expiration")?>:</b></th>
	<th><b><?=word_lang("orders")?>:</b></th>
	<th><b><?=word_lang("delete")?></b></th>
	</tr>
	<?
	while(!$rs->eof)
	{
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
		<td align="center"><input name="priority<?=$rs->row["id_parent"]?>" type="text" style="width:40px" value="<?=$rs->row["priority"]?>"></td>
		<td><input name="title<?=$rs->row["id_parent"]?>" type="text" style="width:250px" value="<?=$rs->row["title"]?>"></td>
		<td align="center"><input name="quantity<?=$rs->row["id_parent"]?>" type="text" style="width:60px"  value="<?=$rs->row["quantity"]?>"></td>
		<td align="center"><input name="price<?=$rs->row["id_parent"]?>" type="text" style="width:60px"  value="<?=float_opt($rs->row["price"],2)?>"></td>
		<td align="center"><input name="days<?=$rs->row["id_parent"]?>" type="text" style="width:40px"  value="<?=$rs->row["days"]?>"></td>
		<td align="center"><div class="link_order">
		<?
		$count_credits=0;
		$sql="select count(id_parent) as count_credits from credits_list where credits=".$rs->row["id_parent"]." group by credits";
		$dr->open($sql);
		if(!$dr->eof)
		{
			$count_credits=$dr->row["count_credits"];
		}
		?><a href="../credits/index.php?credits_type=<?=$rs->row["id_parent"]?>"><?=$count_credits?></a></div>
		</td>
		<td>
		<div class="link_delete"><a href='delete.php?id=<?=$rs->row["id_parent"]?>' onClick="return confirm('<?=word_lang("delete")?>?');"><?=word_lang("delete")?></a></div>
		</td>
		</tr>
		<?
	$tr++;
	$rs->movenext();
	}
	?>
	</table>
	</div></div></div></div></div></div></div></div>
	<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>" style="margin:10px 0px 0px 6px">
	</form><br>
	<?
}
?>










<div style='display:none'>
		<div id='new_box'>
		
		<div class="modal_header"><?=word_lang("credits types")?></div>

<form method="post" action="add.php">
<div class="table_t"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover">
<tr>
<th><b><?=word_lang("priority")?>:</b></th>
<th><b><?=word_lang("title")?>:</b></th>
<th><b><?=word_lang("quantity")?>:</b></th>
<th><b><?=word_lang("price")?>:</b></th>
<th><b><?=word_lang("days till expiration")?>:</b></th>
</tr>
<tr>
<td><input name="priority" type="text" style="width:60px" value="1"></td>
<td><input name="title" type="text" style="width:250px" value='New'></td>
<td><input name="quantity" type="text" style="width:60px" value="1"></td>
<td><input name="price" type="text" style="width:60px" value="1.00"></td>
<td><input name="days" type="text" style="width:40px" value="0"></td>
</tr>
</table>
</div></div></div></div></div></div></div></div>
<input type="submit" class="btn btn-primary" value="<?=word_lang("add")?>" style="margin:10px 0px 0px 6px">
</form>


</div>
</div>















<? include("../inc/end.php");?>