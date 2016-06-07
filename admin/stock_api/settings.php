<?
//Check access
admin_panel_access("settings_stockapi");

if(!defined("site_root")){exit();}
?>

<p>You can set the search in the different media databases.</p>

	<form method="post" action="settings_change.php">


	
	<div class='admin_field'>
	<span><?=word_lang("Default media stock")?>:</span>

	<select name="stock_default" style="width:200px">
		<?
		foreach ($mstocks as $key => $value) 
		{
			if($global_settings[$key."_api"])
			{
				$sel="";
				if($global_settings["stock_default"] == $key)
				{
					$sel="selected";
				}
				?>
				<option value="<?=$key?>" <?=$sel?>><?=$value?></option>
				<?
			}
		}
		?>
	</select>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("Show site stock")?>:</span>
	<input type="checkbox" name="site_api" value="1" <?if($global_settings["site_api"]){echo("checked");}?>><br>
	</div>
	




	
	<div class='admin_field'>
	<input  class="btn btn-primary" type="submit" value="<?=word_lang("save")?>">
	</div>

	</form>

