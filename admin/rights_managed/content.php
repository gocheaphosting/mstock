<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_rightsmanaged");

?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>


<script>
function open_preview(id,value,id_element)
{
	$.colorbox({width:"500",height:"", href:"preview.php?id="+id+"&events="+value+"&id_element="+id_element});
}
</script>

<?
include("rights_managed_functions.php");




$sql="select title from rights_managed where id=".(int)$_GET["id"];
$rs->open($sql);
if(!$rs->eof)
{
	?>
	<div class="back"><a href="index.php" class="btn btn-mini btn-primary btn-sm"><i class="icon-arrow-left icon-white fa fa-arrow-left"></i> <?=word_lang("back")?></a></div>
	<a class="btn btn-success toright" href="javascript:open_preview('<?=$_GET["id"]?>','step_add',0)" style="margin-left:20px"><i class="icon-folder-open icon-white fa fa-plus"></i>&nbsp; <?=word_lang("add step")?></a>

	<h1>
		<?
			echo($rs->row["title"]);
		?>:
	</h1>
	
	
	<div class="box box_padding">
	<?
	$itg="";
	$nlimit=0;
	build_rights_managed_admin(0);
	echo($itg);
}
?>
</div>

<? include("../inc/end.php");?>