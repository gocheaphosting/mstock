<?if(!isset($_SESSION["people_id"])){header("location:login.php");}?>

<h1><?=word_lang("affiliate")?> &mdash; <?=word_lang("payout method")?></h1>

<form method="post" action="affiliate_change.php">
<?
if($global_settings["payout_set"])
{
	$sql="select payout_limit from users where id_parent=".$_SESSION["people_id"];
	$ds->open($sql);
	if(!$ds->eof)
	{
		?>
		<div class="form_field">
		<span><?=word_lang("Set your balance threshold for payout")?> (<?=$currency_code1?>):</span>
		<input class="ibox form-control" type="text" name="payout_limit" value="<?=float_opt($ds->row["payout_limit"],2)?>" style="width:100px">	
		</div>
		<?
	}
}


$sql="select paypal,moneybookers,dwolla,qiwi,webmoney,bank_name,bank_account,payson from users where id_parent=".(int)$_SESSION["people_id"];
$rs->open($sql);
if(!$rs->eof)
{
	$sql="select * from payout where activ=1";
	$ds->open($sql);
	if(!$ds->eof)
	{
		while(!$ds->eof)
		{
			?>
			<div class="form_field">
				<span><?=$ds->row["title"]?>:</span>
				<?
				if($ds->row["svalue"]=="bank")
				{
					?>
					<input class="ibox form-control" type="text" name="bank_account" value="<?=$rs->row["bank_account"]?>" style="width:230px">
					<select class="ibox form-control"  name="bank_name" style="width:150px">
						<option value=""><?=word_lang("banks")?></option>
						<?
							$sql="select title from banks order by title";
							$dn->open($sql);
							while(!$dn->eof)
							{
								$sel="";
								if($dn->row["title"]==$rs->row["bank_name"])
								{
									$sel="selected";
								}
								?>
								<option value="<?=$dn->row["title"]?>" <?=$sel?>><?=$dn->row["title"]?></option>
								<?
								$dn->movenext();
							}
						?>
					</select>
					<?
				}
				else
				{
					?>
					<input class="ibox form-control" type="text" name="<?=$ds->row["svalue"]?>" value="<?=$rs->row[$ds->row["svalue"]]?>" style="width:230px">
					<?
				}
				?>
			</div>		
			<?
			$ds->movenext();
		}
		?>
		<input type="submit" value="<?=word_lang("change")?>" class="isubmit">		
		<?
	}
}
?>
</form>