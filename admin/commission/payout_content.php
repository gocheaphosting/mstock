<?if(!defined("site_root")){exit();}?>


<h1><?=word_lang("refund")?></h1>


<form method="post" action="<?=$url?>">
<div class="form_field">
	<span><?=word_lang("total")?>:</span>
	<input type="text" name="total" style="width:300px" value="1.00">
</div>

<div class="form_field">
	<span><?=word_lang("description")?>:</span>
	<textarea name="description" style="width:300px;height:40px"></textarea>
</div>

<div class="form_field">
	<span><?=word_lang("user")?>:</span>
	<select name="user" style="width:300px">
	<option value=""></option>
	<?
	$sql="select id_parent,login from users where utype='seller' or utype='common' or utype='affiliate' order by login";
	$ds->open($sql);
	while(!$ds->eof)
	{
		$sel="";
		if(isset($_GET["user"]) and $_GET["user"]==$ds->row["id_parent"])
		{
			$sel="selected";
		}
		?>
		<option value="<?=$ds->row["id_parent"]?>" <?=$sel?>><?=$ds->row["login"]?></option>
		<?
		$ds->movenext();
	}
	?>
	</select>
</div>


<div class="form_field">
<span><?=word_lang("payout method")?>:</span>
<?
				$sql="select paypal,moneybookers,dwolla,qiwi,webmoney,bank_name,bank_account,payson  from users where id_parent=".(int)$_GET["user"];
				$dr->open($sql);
				if(!$dr->eof)
				{
					$sql="select * from payout where activ=1";
					$ds->open($sql);
					while(!$ds->eof)
					{
						$sel="";
						if(isset($_GET["method"]) and $_GET["method"]==$ds->row["svalue"])
						{
							$sel="checked";
						}
						
						if($ds->row["svalue"]=="bank")
						{
							if($dr->row["bank_name"]!="" and $dr->row["bank_account"]!="")
							{
								?>
									<div style="margin-bottom:5px"><input type="radio" name="method" value="<?=$ds->row["svalue"]?>" <?=$sel?>>&nbsp;&nbsp;<div style="display:inline;" class="text_<?=$ds->row["svalue"]?>" ><?=str_replace(" account","",$ds->row["title"])?></div>&nbsp;&nbsp;<font class="small_text">[<?=$dr->row["bank_name"]?>: <?=$dr->row["bank_account"]?>]<input type="hidden" name="bank_name" value="<?=$dr->row["bank_name"]?>"><input type="hidden" name="bank_account" value="<?=$dr->row["bank_account"]?>"></font></div>
								<?
							}
						}
						else
						{
							$t="site_".$ds->row["svalue"]."_account";
							$tt=$$t;
							if($dr->row[$ds->row["svalue"]]!="" and $tt!="")
							{
								?>
									<div style="margin-bottom:5px"><input type="radio" name="method" value="<?=$ds->row["svalue"]?>" <?=$sel?>>&nbsp;&nbsp;<div style="display:inline;" class="text_<?=$ds->row["svalue"]?>" ><?=str_replace(" account","",$ds->row["title"])?></div>&nbsp;&nbsp;<font class="small_text">[<?=$dr->row[$ds->row["svalue"]]?>]</font></div>
								<?
							}
						}
						$ds->movenext();
					}
				}
?>
<div style="margin-bottom:5px"><input type="radio" name="method" value="other" <?if(isset($_GET["method"]) and $_GET["method"]=="other"){echo("checked");}?>>&nbsp;&nbsp;<div style="display:inline;" class="text_other" >Other</div></div>
</div>



<div class="form_field">
	<input type="submit" class="btn btn-primary" value="<?=word_lang("pay now")?>">
</div>
</form>
