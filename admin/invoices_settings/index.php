<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("settings_invoices");
?>
<? include("../inc/begin.php");?>






<h1><?=word_lang("Invoices")?></h1>




<div class="box box_padding">

<div class="subheader"><?=word_lang("settings")?></div>
<div class="subheader_text">
	<form method="post" action="change.php">

	<div class='admin_field'>
	<span><?=word_lang("Publish an invoice automatically when a transaction is successful")?>:</span>
	<small>Sometimes you have to add some text comment to an invoice and only then make it available for a user. In this case you should disable the checkbox. When an invoice is published you may not change it.</small><br>
	<input type="checkbox" name="invoice_publish" value="1" <?if($global_settings["invoice_publish"]){echo("checked");}?>><br>
	
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("Invoice prefix")?>:</span>
	<input type="text" name="invoice_prefix" value="<?=$global_settings["invoice_prefix"]?>" style="width:250px"><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("Next invoice number")?>:</span>
	<input type="text" name="invoice_number" value="<?=$global_settings["invoice_number"]?>" style="width:250px;display:inline"><!--&nbsp;&nbsp;<a href="regenerate_invoices.php" class="btn btn-danger"><i class="fa fa-refresh"></i>
<?=word_lang("Regenerate invoices for old orders")?></a><br>-->
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("Credit notes prefix")?>:</span>
	<input type="text" name="credit_notes_prefix" value="<?=$global_settings["credit_notes_prefix"]?>" style="width:250px"><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("Next credit notes number")?>:</span>
	<input type="text" name="credit_notes_number" value="<?=$global_settings["credit_notes_number"]?>" style="width:250px"><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("Company name")?>:</span>
	<input type="text" name="company_name" value="<?=$global_settings["company_name"]?>" style="width:250px"><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("Company address")?> 1:</span>
	<input type="text" name="company_address1" value="<?=$global_settings["company_address1"]?>" style="width:250px"><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("Company address")?> 2:</span>
	<input type="text" name="company_address2" value="<?=$global_settings["company_address2"]?>" style="width:250px"><br>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("Country")?>:</span>
	<select name="company_country" style="width:250px">
	<option value=""></option>
			<?
			$sql="select name from countries where activ=1 order by priority,name";
			$dd->open($sql);
			while(!$dd->eof)
			{
				$sel="";
				if($dd->row["name"]==$global_settings["company_country"]){$sel="selected";}
	
				?>
				<option value="<?=$dd->row["name"]?>" <?=$sel?>><?=$dd->row["name"]?></option>
				<?
				
				$dd->movenext();
			}
			?>
	</select>
	</div>
	
	<div class='admin_field'>
	<span><?=word_lang("EU VAT Reg No")?> (<?=word_lang("only for EU")?>):</span>
	<input type="text" name="company_vat_number" value="<?=$global_settings["company_vat_number"]?>" style="width:250px"><br>
	</div>
	

	
	<div class='admin_field'>
	<input  class="btn btn-primary" type="submit" value="<?=word_lang("save")?>">
	</div>

	</form>
</div>



<div class="subheader"><?=word_lang("Logo")?></div>
<div class="subheader_text">

	<form method="post" action="upload_logo.php" Enctype="multipart/form-data">
	
	

	<p>You should upload an invoice's logo. (*.jpg)</p>
	
	<div class='admin_field'>
		<span><?=word_lang("file")?>:</span>
		<input name="logo_photo" type="file"><br>
		<?
		if(file_exists($DOCUMENT_ROOT."/content/invoice_logo.jpg"))
		{
			?><img src="<?=site_root?>/content/invoice_logo.jpg" style="margin-bottom:3px;border:1px solid #f5f5f5"><br><a href="delete_logo.php"><i class="fa fa-remove"> </i> <?=word_lang("delete")?></a><?
		}
		?>
	</div>
	
	<div class='admin_field'>
		<span><?=word_lang("size")?> (<?=word_lang("pixels")?>):</span>
		<input type="text" name="invoice_logo_size" value="<?=$global_settings["invoice_logo_size"]?>" style="width:250px"><br>
	</div>

	<div class='admin_field'>
	<input  class="btn btn-primary" type="submit" value="<?=word_lang("save")?>">
	</div>

	</form>


</div>

<div class="subheader"><?=word_lang("template")?></div>
<div class="subheader_text">
	You could find the invoice's template here:<br>
	<b><?=site_root?>/templates/invoice/invoice.tpl</b>

</div>



</div>











<? include("../inc/end.php");?>