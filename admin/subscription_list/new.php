<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_subscription");
?>
<? include("../inc/begin.php");?>
<? include("../function/show.php");?>
<? include("../function/upload.php");?>

<div class="back"><a href="index.php" class="btn btn-mini"><i class="icon-arrow-left"></i> <?=word_lang("back")?></a></div>

<h1><?=word_lang("new")?>:</h1>

<div class="box box_padding">
	<form method="post" action="add.php">
	<div class='admin_field'>
	<span><?=word_lang("subscription")?>:</span>
	<select name="subscription" style="width:300px" class="ibox form-control">
		<?
		$sql="select id_parent,title from subscription order by priority";
		$ds->open($sql);
		while(!$ds->eof)
		{
			?>
			<option value="<?=$ds->row["id_parent"]?>"><?=$ds->row["title"]?></option>
			<?
			$ds->movenext();
		}
		?>
	</select>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("user")?>:</span>
	<input type="text" name="user" value="<?=$rs->row["user"]?>" style="width:150px" class="ibox form-control">
	</div>
	
	<div class='admin_field'>
	<input type="submit" class="btn btn-primary" value="<?=word_lang("add")?>">
	</div>
	</form>

</div>


<? include("../inc/end.php");?>