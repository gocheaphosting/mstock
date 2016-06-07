<?
//Check access
admin_panel_access("settings_stockapi");

if(!defined("site_root")){exit();}
?>

<div class="subheader"><?=word_lang("overview")?></div>
<div class="subheader_text">

<p>
<b>Documentation:</b><br>
<a href="https://developers.shutterstock.com/">developers.shutterstock.com</a>
</p>

</div>
<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">

	<form method="post" action="shutterstock_change.php">


	
	<div class='admin_field'>
	<span>Shutterstock API:</span>
	<input type="checkbox" name="shutterstock_api" value="1" <?if($global_settings["shutterstock_api"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span>Client ID:</span>
	<input type="text" name="shutterstock_id" value="<?=$global_settings["shutterstock_id"]?>" style="width:350px"><br>
	</div>
	
	<div class='admin_field'>
	<span>Client Secret:</span>
	<input type="text" name="shutterstock_secret" value="<?=$global_settings["shutterstock_secret"]?>" style="width:350px"><br>
	</div>
	
	<div class='admin_field'>
	<span>Affiliate URL:</span>
	<input type="text" name="shutterstock_affiliate" value="<?=$global_settings["shutterstock_affiliate"]?>" style="width:350px">
	<small>Shutterstock uses Impact Radius affiliate program. You will get an affiliate link like that:<br>http://shutterstock.7eer.net/c/202194/42119/1305<br>You have to set it as Affiliate URL</small>
	</div>
	
	<div class='admin_field'>
	<span>Shutterstock <?=word_lang("Contributor")?>:</span>
	<input type="text" name="shutterstock_contributor" value="<?=$global_settings["shutterstock_contributor"]?>" style="width:350px"><br>
	</div>
	
	<div class='admin_field'>
	<span>Shutterstock <?=word_lang("categories")?>:</span>
	<select name="shutterstock_category" style="width:350px">
		<option value="-1"></option>
		<?
		$sql="select id,title from category_stock where stock='shutterstock' order by title";
		$rs->open($sql);
		while(!$rs->eof)
		{
			$sel="";
			if($rs->row["id"] == $global_settings["shutterstock_category"])
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
	<a href="shutterstock_categories.php" class="btn btn-default" style="margin-top:2px"><i class="fa fa-refresh"></i> <?=word_lang("refresh")?></a>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("Create internal pages for files")?>:</span>
	<input type="checkbox" name="shutterstock_pages" value="1" <?if($global_settings["shutterstock_pages"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("files")?>:</span>
	<input type="checkbox" name="shutterstock_files" value="1" <?if($global_settings["shutterstock_files"]){echo("checked");}?>><br>
	</div>

	<div class='admin_field'>
	<span><?=word_lang("Prints on Demand")?>:</span>
	<input type="checkbox" name="shutterstock_prints" value="1" <?if($global_settings["shutterstock_prints"]){echo("checked");}?>><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("by default")?>:</span>
	<select name="shutterstock_show" style="width:350px">
		<option value="1" <?if($global_settings["shutterstock_show"] == 1){echo("selected");}?>><?=word_lang("files")?></option>
		<option value="2" <?if($global_settings["shutterstock_show"] == 2){echo("selected");}?>><?=word_lang("prints")?></option>
	</select>
	</div>
	
	<div class='admin_field'>
	<input  class="btn btn-primary" type="submit" value="<?=word_lang("save")?>">
	</div>

	</form>

</div>
