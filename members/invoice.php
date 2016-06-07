<?$site="invoice";$site2="";?>
<?include("../admin/function/db.php");?>
<?if(!isset($_SESSION["people_id"])){header("location:login.php");exit();}?>
<?include("../inc/header.php");?>
<?include("profile_top.php");?>

<?
$flag = false;
$refund = false;


$sql="select order_id,order_type,refund from invoices where invoice_number=".(int)@$_GET["id"]." and status=1";
$rs->open($sql);
if(!$rs->eof)
{
	if($rs->row["refund"]==1)
	{
		$refund = true;
	}
	
	if($rs->row["order_type"]=="orders")
	{
		$sql="select id from orders where id=".$rs->row["order_id"]." and user=".(int)$_SESSION["people_id"];
		$ds->open($sql);
		if(!$ds->eof)
		{
			$flag = true;	
		}	
	}
	if($rs->row["order_type"]=="credits")
	{
		$sql="select id_parent from credits_list where id_parent=".$rs->row["order_id"]." and user='".result($_SESSION["people_login"])."'";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$flag = true;		
		}	
	}
	if($rs->row["order_type"]=="subscription")
	{
		$sql="select id_parent from subscription_list where id_parent=".$rs->row["order_id"]." and user='".result($_SESSION["people_login"])."'";
		$ds->open($sql);
		if(!$ds->eof)
		{
			$flag = true;		
		}	
	}
}

if($flag == true)
{
?>
	<a href="invoice_html.php?id=<?=(int)@$_GET["id"]?>" target="blank" class="btn btn-success pull-right" style="color:#FFFFFF;text-decoration:none"><i class="fa fa-file-text"> </i>
			  <?=word_lang("download")?> HTML</a>
			<a href="invoice_pdf.php?id=<?=(int)@$_GET["id"]?>" target="blank" class="btn btn-danger pull-right" style="margin-right: 5px;color:#FFFFFF;text-decoration:none"><i class="fa fa-file-pdf-o"> </i>
			 <?=word_lang("download")?> PDF</a>

	<?
	if($refund)
	{
		?>
		<h1><?=word_lang("Credit notes")?> #<?=$global_settings["credit_notes_prefix"]?><?=(int)@$_GET["id"]?></h1>
		<?
	}
	else
	{
		?>
		<h1><?=word_lang("Invoice")?> #<?=$global_settings["invoice_prefix"]?><?=(int)@$_GET["id"]?></h1>
		<?	
	}
	?>
	





<?
	$invoice_content = '';
	include("../admin/invoices/invoice_content.php");
	echo($invoice_content);
}
?>






<?include("profile_bottom.php");?>
<?include("../inc/footer.php");?>