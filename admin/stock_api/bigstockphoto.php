<?
//Check access
admin_panel_access("settings_stockapi");

if(!defined("site_root")){exit();}
?>

<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">

<p>
<b>Documentation:</b><br>
<a href="https://www.bigstockphoto.com/partners/">https://www.bigstockphoto.com/partners/</a>
</p>

</div>
<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">

	<form method="post" action="bigstockphoto_change.php">


	
	<div class='admin_field'>
	<span>Bigstockphoto API:</span>
	<input type="checkbox" name="bigstockphoto_api" value="1" <?if($global_settings["bigstockphoto_api"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span>API Account ID:</span>
	<input type="text" name="bigstockphoto_id" value="<?=$global_settings["bigstockphoto_id"]?>" style="width:350px"><br>
	</div>
	
	<div class='admin_field'>
	<span>Affiliate URL:</span>
	<input type="text" name="bigstockphoto_affiliate" value="<?=$global_settings["bigstockphoto_affiliate"]?>" style="width:650px">
	<small>You can use the syntax in the affiliate link:<br>{URL} - URL of the publication page on bigstockphoto. You redirect visitors to the URL with your affiliate link.<br>{URL_ENCODED} - encoded publication URL<br>{ID} - publication ID on bigstockphoto.<br><br>
	<b>Example:</b> Bigstockphoto uses Impact Radius affiliate program. You will get an affiliate link like that:<br> http://bigstock.7eer.net/c/202194/42119/1305</br> You should set Affiliate URL:<br>
	http://bigstock.7eer.net/c/202194/42119/1305?u={URL_ENCODED}
	</small>
	</div>
	
	<div class='admin_field'>
	<span>Bigstockphoto <?=word_lang("Contributor")?>:</span>
	<input type="text" name="bigstockphoto_contributor" value="<?=$global_settings["bigstockphoto_contributor"]?>" style="width:350px"><br>
	</div>
	
	<div class='admin_field'>
	<span>Bigstockphoto <?=word_lang("categories")?>:</span>
	<select name="bigstockphoto_category" style="width:350px">
		<option value="-1"></option>
		<?
		$sql="select id,title from category_stock where stock='bigstockphoto' order by title";
		$rs->open($sql);
		while(!$rs->eof)
		{
			$sel="";
			if($rs->row["title"] == $global_settings["bigstockphoto_category"])
			{
				$sel="selected";
			}
			?>
			<option value="<?=$rs->row["title"]?>" <?=$sel?>><?=$rs->row["title"]?></option>
			<?
			$rs->movenext();
		}
		?>
	</select>
	<a href="bigstockphoto_categories.php" class="btn btn-default" style="margin-top:2px"><i class="fa fa-refresh"></i> <?=word_lang("refresh")?></a>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("Create internal pages for files")?>:</span>
	<input type="checkbox" name="bigstockphoto_pages" value="1" <?if($global_settings["bigstockphoto_pages"]){echo("checked");}?>><br>
	</div>

	<div class='admin_field'>
	<span><?=word_lang("files")?>:</span>
	<input type="checkbox" name="bigstockphoto_files" value="1" <?if($global_settings["bigstockphoto_files"]){echo("checked");}?>><br>
	</div>

	<div class='admin_field'>
	<span><?=word_lang("Prints on Demand")?>:</span>
	<input type="checkbox" name="bigstockphoto_prints" value="1" <?if($global_settings["bigstockphoto_prints"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("by default")?>:</span>
	<select name="bigstockphoto_show" style="width:350px">
		<option value="1" <?if($global_settings["bigstockphoto_show"] == 1){echo("selected");}?>><?=word_lang("files")?></option>
		<option value="2" <?if($global_settings["bigstockphoto_show"] == 2){echo("selected");}?>><?=word_lang("prints")?></option>
	</select>
	</div>



	
	<div class='admin_field'>
	<input  class="btn btn-primary" type="submit" value="<?=word_lang("save")?>">
	</div>

	</form>

</div>
