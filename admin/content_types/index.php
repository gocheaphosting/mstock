<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_content_types");
?>
<? include("../inc/begin.php");?>




<script>
$(document).ready(function(){
	$("#add_new").colorbox({width:"400",height:"270", inline:true, href:"#new_box",scrolling:false});
});
</script>


<a class="btn btn-success toright" id="add_new" href="#"><i class="icon-th-large icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add")?></a>

<div style='display:none'>
		<div id='new_box'>
		
		<div class="modal_header"><?=word_lang("content type")?></div>


<form method="post" action="add.php">



<div class="form_field">
<span><b><?=word_lang("title")?>:</b></span><input name="title" type="text" style="width:250px">
</div>

<div class="form_field">
<span><b><?=word_lang("priority")?>:</b></span><input name="priority" type="text" style="width:60px" value="1">
</div>

<div class="form_field">
<input type="submit" class="btn btn-primary" value="<?=word_lang("add")?>">
</div>
</form>


		</div>
</div>




<h1><?=word_lang("content type")?>:</h1>


<p>To set a <a href="../subscription/"><b>Subscription plan</b></a> you should define Content Types first. This is a method to divide all files into several global categories. For example: Premium files, usual files and etc.</p>

<p>The content type <b>by default</b> is <b><?=$global_settings["content_type"]?></b>. You can change it in <a href="../settings/site.php">Site settings</a></p>

<p>You are able to bulk change a content type for the publications here: <a href="../categories/">Categories -> Select action</a> and <a href="../catalog/">Catalog -> Select action</a></p>


<br>

<?
$sql="select id_parent,priority,name from content_type order by priority";
$rs->open($sql);
if(!$rs->eof)
{
	$tr=1;
	?>
	<form method="post" action="change.php">
	<div class="table_t2"><div class="table_b"><div class="table_l"><div class="table_r"><div class="table_bl"><div class="table_br"><div class="table_tl"><div class="table_tr">
	<table border="0" cellpadding="3" cellspacing="1" class="table_admin table table-striped table-hover" style="width:100%">
	<tr>
	<th><b><?=word_lang("priority")?>:</b></th>
	<th style="width:70%"><b><?=word_lang("title")?>:</b></th>
	<th><b><?=word_lang("files")?>:</b></th>
	<th><b><?=word_lang("delete")?></b></th>
	</tr>
	<?
	while(!$rs->eof)
	{
		//Count files.
		$count_types=0;
		
		$sql="select count(id_parent) as count_types from photos where content_type='".$rs->row["name"]."' group by content_type";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$count_types+=$ds->row["count_types"];
		}
		
		$sql="select count(id_parent) as count_types from videos where content_type='".$rs->row["name"]."' group by content_type";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$count_types+=$ds->row["count_types"];
		}
		
		$sql="select count(id_parent) as count_types from audio where content_type='".$rs->row["name"]."' group by content_type";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$count_types+=$ds->row["count_types"];
		}
		
		$sql="select count(id_parent) as count_types from vector where content_type='".$rs->row["name"]."' group by content_type";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$count_types+=$ds->row["count_types"];
		}
		?>
		<tr <?if($tr%2==0){echo("class='snd'");}?> valign="top">
		<td align="center"><input name="priority<?=$rs->row["id_parent"]?>" type="text" style="width:40px" value="<?=$rs->row["priority"]?>"></td>
		<td><input name="title<?=$rs->row["id_parent"]?>" type="text" style="width:250px" value="<?=$rs->row["name"]?>"></td>
		<td><div class="link_file"><a href="../catalog/index.php?pub_ctype=<?=$rs->row["name"]?>"><?=$count_types?></a></div></td>
		<td>
		<div class="link_delete"><a href='delete.php?id=<?=$rs->row["id_parent"]?>'><?=word_lang("delete")?></a></div>
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





<? include("../inc/end.php");?>