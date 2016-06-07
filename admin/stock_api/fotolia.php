<?
//Check access
admin_panel_access("settings_stockapi");

if(!defined("site_root")){exit();}
?>

<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">

<p>
<b>Documentation:</b><br>
<a href="https://www.fotolia.com/Services/API/">https://www.fotolia.com/Services/API</a>
</p>


</div>
<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">

	<form method="post" action="fotolia_change.php">


	
	<div class='admin_field'>
	<span>Fotolia API:</span>
	<input type="checkbox" name="fotolia_api" value="1" <?if($global_settings["fotolia_api"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span>Fotolia API Key:</span>
	<input type="text" name="fotolia_id" value="<?=$global_settings["fotolia_id"]?>" style="width:350px"><br>
	</div>
	
	<div class='admin_field'>
	<span>Your Fotolia ID:</span>
	<input type="text" name="fotolia_account" value="<?=$global_settings["fotolia_account"]?>" style="width:350px">
	<small>To build a correct affiliate link.</small>
	</div>
	
	<div class='admin_field'>
	<span>Fotolia <?=word_lang("Contributor")?> ID:</span>
	<input type="text" name="fotolia_contributor" value="<?=$global_settings["fotolia_contributor"]?>" style="width:350px"><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("keywords")?> (<?=word_lang("by default")?>):</span>
	<input type="text" name="fotolia_query" value="<?=$global_settings["fotolia_query"]?>" style="width:350px">
	<small>It is required. Otherwise the default listing will be empty.</small>
	</div>
	
	<div class='admin_field'>
	<span>Fotolia <?=word_lang("categories")?>:</span>
	<select name="fotolia_category" style="width:350px">
		<option value="-1"></option>
		<?
		$sql="select id,title from category_stock where stock='fotolia' order by title";
		$rs->open($sql);
		while(!$rs->eof)
		{
			$sel="";
			if($rs->row["id"] == $global_settings["fotolia_category"])
			{
				$sel="selected";
			}
			?>
			<option value="<?=$rs->row["id"]?>" <?=$sel?>><?=$rs->row["title"]?></option>
			<?
			$rs->movenext();
		}
		?>
	</select>
	<a href="fotolia_categories.php" class="btn btn-default" style="margin-top:2px"><i class="fa fa-refresh"></i> <?=word_lang("refresh")?></a>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("Create internal pages for files")?>:</span>
	<input type="checkbox" name="fotolia_pages" value="1" <?if($global_settings["fotolia_pages"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("files")?>:</span>
	<input type="checkbox" name="fotolia_files" value="1" <?if($global_settings["fotolia_files"]){echo("checked");}?>><br>
	</div>

	<div class='admin_field'>
	<span><?=word_lang("Prints on Demand")?>:</span>
	<input type="checkbox" name="fotolia_prints" value="1" <?if($global_settings["fotolia_prints"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("by default")?>:</span>
	<select name="fotolia_show" style="width:350px">
		<option value="1" <?if($global_settings["fotolia_show"] == 1){echo("selected");}?>><?=word_lang("files")?></option>
		<option value="2" <?if($global_settings["fotolia_show"] == 2){echo("selected");}?>><?=word_lang("prints")?></option>
	</select>
	</div>


	
	<div class='admin_field'>
	<input  class="btn btn-primary" type="submit" value="<?=word_lang("save")?>">
	</div>

	</form>

</div>
