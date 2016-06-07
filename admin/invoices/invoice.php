<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_invoices");
?>
<? include("../inc/begin.php");?>

<?
$sql="select id,invoice_number,order_id,order_type,comments,refund from invoices where invoice_number=".(int)$_GET["id"];
$ds->open($sql);
if(!$ds->eof)
{
	?>
	<a href="invoice_html.php?id=<?=(int)@$_GET["id"]?>" target="blank" class="btn btn-success pull-right" style="margin-right: 25px;"><i class="fa fa-file-text"> </i>
	  <?=word_lang("download")?> HTML</a>
	<a href="invoice_pdf.php?id=<?=(int)@$_GET["id"]?>" target="blank" class="btn btn-danger pull-right" style="margin-right: 5px;"><i class="fa fa-file-pdf-o"> </i>
	 <?=word_lang("download")?> PDF</a>
	 <?
	if($ds->row["refund"] != 1)
	{
		 ?>
		 <a href="refund.php?id=<?=(int)@$_GET["id"]?>" class="btn btn-warning pull-right" style="margin-right: 5px;"><i class="fa fa-repeat"> </i>
		 <?=word_lang("Refund money")?></a>
		 <?			
	}
	 if(!isset($_GET["change"]))
	{
		 ?>
		 <a href="invoice.php?id=<?=(int)@$_GET["id"]?>&change=1" class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-edit"> </i>
		 <?=word_lang("edit")?></a>
		 <?
	 }


	if($ds->row["refund"] == 1)
	{
		 ?>
		<h1 style="margin-left:25px"><?=word_lang("Credit notes")?> 
		<small><?=$global_settings["credit_notes_prefix"]?><?=@$_GET["id"]?></small></h1>
		<?
	}
	else
	{
		 ?>
		<h1 style="margin-left:25px"><?=word_lang("Invoice")?> 
		<small><?=$global_settings["invoice_prefix"]?><?=@$_GET["id"]?></small></h1>
		<?	
	}
	
	if(isset($_GET["change"]))
	{
			?>
			<form method="post" action="change.php?id=<?=$ds->row["id"]?>" style="margin-left:20px">
			
			<div class="form_field">
				<span><?=word_lang("Invoice Number")?>:</span>
				<input name="invoice_number" type="text" value="<?=@$_GET["id"]?>" style="width:120px" class="form-control">
			</div>
			
			<div class="form_field">
				<span><?=word_lang("Note on Invoice")?>:</span>
				<textarea name="comments" type="text" style="width:500px;height:100px" class="form-control"><?=$ds->row["comments"]?></textarea>
			</div>
			
			<div class="form_field">
				<input type="submit" class="btn btn-primary" value="<?=word_lang("change")?>">
			</div>
	
			</form><br>
			<?
	}
}
?>



<? 
$invoice_content = '';
include("invoice_content.php");
echo($invoice_content);
?>





<? include("../inc/end.php");?>