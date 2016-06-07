<?if(!defined("site_root")){exit();}?>
<p><b><?=word_lang("balance")?>: <span class="price"><?=float_opt(credits_balance(),2)?> <?=word_lang("credits")?></span></b></p>

<?
//Текущая страница
if(!isset($_GET["str"])){$str=1;}
else{$str=(int)$_GET["str"];}

//Количество новостей на странице
$kolvo=$global_settings["k_str"];

//Количество страниц на странице
$kolvo2=k_str2;



$sql="select quantity,data,title,approved,payment,expiration_date,id_parent,total from credits_list where user='".result($_SESSION["people_login"])."' order by data desc";
$rs->open($sql);
if(!$rs->eof)
{
?>
<table border="0" cellpadding="0" cellspacing="0"  class="profile_table" width="100%">
<tr>
<th class='hidden-phone hidden-tablet'><?=word_lang("date")?></th>
<th><?=word_lang("title")?></th>
<th class='hidden-phone hidden-tablet'><?=word_lang("quantity")?></th>
<th class='hidden-phone hidden-tablet'><?=word_lang("expiration date")?></th>
<th><?=word_lang("approved")?></th>
<th><?=word_lang("Invoice")?></th>
</tr>
<?
$tr=1;
$n=0;
while(!$rs->eof)
{
if($n>=$kolvo*($str-1) and $n<$kolvo*$str)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td class='hidden-phone hidden-tablet'><div class="link_date"><?=date(date_format,$rs->row["data"])?></div></td>
<td nowrap>
<div class="link_order">

<?=$rs->row["title"]?>
<?if($rs->row["quantity"]>0){?>
</a>
<?}?>
</div>
</td>
<td nowrap class='hidden-phone hidden-tablet'><?=float_opt($rs->row["quantity"],2)?></td>
<td class='hidden-phone hidden-tablet'>
<?
if($rs->row["quantity"]>0)
{
	if($rs->row["expiration_date"]==0)
	{
		echo(word_lang("never"));
	}
	else
	{
		echo("<div class='link_date'>".date(date_short,$rs->row["expiration_date"])."</div>");
	}
}
else
{
	echo("&#8212;");
}
?>
</td>
<td><?if($rs->row["approved"]==1){echo("<div class='link_approved'>".word_lang("approved")."</div>");}else{echo("<div class='link_pending'>".word_lang("pending")."</div>");}?></td>


<td>
<?
if($rs->row["quantity"]>0 and $rs->row["total"]>0)
{
	$invoice_number = "";
	
	$sql="select invoice_number,status,refund from invoices where order_type='credits' and order_id=".$rs->row["id_parent"]." order by id";
	$ds->open($sql);
	while(!$ds->eof)
	{
		if($ds->row["status"])
		{
			if($ds->row["refund"] == 1)
			{
				$invoice_number = word_lang("Refund money").": #".$global_settings["credit_notes_prefix"].$ds->row["invoice_number"];
				$link_class="style='color:red'";
			}
			else
			{
				$invoice_number = "#".$global_settings["invoice_prefix"].$ds->row["invoice_number"];
				$link_class="";
			}
			
			
			?>
			<a href="invoice.php?id=<?=$ds->row["invoice_number"]?>" <?=$link_class?>>
			 <?=$invoice_number?></a><br>
			<?
		}
		
		$ds->movenext();
	}
}
?>

</td>
</tr>
<?
}
$tr++;
$rs->movenext();
}
?>
</table>
<?
echo(paging($n,$str,$kolvo,$kolvo2,"credits.php","&d=2"));
}
else
{
?>
<p><?=word_lang("not found")?>.</p>
<?
}
?>