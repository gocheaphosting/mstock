<?if(!defined("site_root")){exit();}?>


<?
$subscription_limit="Credits";
$sql="select name from subscription_limit where activ=1";
$rs->open($sql);
if(!$rs->eof)
{
	$subscription_limit=$rs->row["name"];
}


$sql="select title,user,data1,data2,bandwidth,bandwidth_limit,subscription,approved,id_parent,bandwidth_daily,bandwidth_daily_limit,bandwidth_date,total from subscription_list where user='".result($_SESSION["people_login"])."' order by data2 desc";
$ds->open($sql);
if(!$ds->eof)
{
?>

<table border="0" cellpadding="0" cellspacing="0"   class="profile_table" width="100%">
<tr>
<th><b><?=word_lang("subscription")?>:</b></th>
<th class='hidden-phone hidden-tablet'><b><?=word_lang("limit")?> (<?=$subscription_limit?>):</b></th>
<th class='hidden-phone hidden-tablet'><b><?=word_lang("daily limit")?>:</b></th>
<th class='hidden-phone hidden-tablet'><b><?=word_lang("content type")?>:</b></th>
<th class='hidden-phone hidden-tablet'><b><?=word_lang("setup date")?>:</b></th>
<th><b><?=word_lang("expiration date")?>:</b></th>
<th><b><?=word_lang("status")?>:</b></th>
<th><b><?=word_lang("Invoice")?>:</b></th>
</tr>

<?
$tr=1;
while(!$ds->eof)
{
?>
<tr <?if($tr%2==0){echo("class='snd'");}?>>
<td><div class="link_subscription"><?=$ds->row["title"]?></div></td>
<td class='hidden-phone hidden-tablet'><?

$bandwidth=$ds->row["bandwidth"];
$bandwidth_text="";
if($subscription_limit=="Bandwidth")
{
	$bandwidth=float_opt($ds->row["bandwidth"],3);
	$bandwidth_text="Mb.";
}
if($subscription_limit=="Credits")
{
	$bandwidth=float_opt($ds->row["bandwidth"],2);
}
echo($bandwidth);

?>(<?=$ds->row["bandwidth_limit"]?>) <?=$bandwidth_text?></td>
<td class='hidden-phone hidden-tablet'>
<?
	if($ds->row["bandwidth_daily_limit"]!=0)
	{
		if(date("j")==$ds->row["bandwidth_date"])
		{
			?><?=$ds->row["bandwidth_daily"]?> (<?=$ds->row["bandwidth_daily_limit"]?>) <?=$bandwidth_text?><?
		}
		else
		{
			?>0 (<?=$ds->row["bandwidth_daily_limit"]?>) <?=$bandwidth_text?><?
		}
	}
	else
	{
		echo(word_lang("no"));
	}
?>
</td>
<td class='hidden-phone hidden-tablet'><?
$sql="select * from subscription where id_parent=".$ds->row["subscription"];
$rs->open($sql);
if(!$rs->eof)
{
echo(str_replace("|","&nbsp;+&nbsp;",$rs->row["content_type"]));
}
?></td>
<td class='hidden-phone hidden-tablet'><div class="link_date"><?=date(datetime_format,$ds->row["data1"])?></div></td>
<td><div class="link_date"><?=date(datetime_format,$ds->row["data2"])?></div></td>
<td><?if($ds->row["approved"]==1){echo("<div class='link_approved'>".word_lang("approved")."</div>");}else{echo("<div class='link_pending'>".word_lang("pending")."</div>");}?></td>
<td>
<?
if($ds->row["total"]>0)
{
	$invoice_number = "";
	
	$sql="select invoice_number,status,refund from invoices where order_type='subscription' and order_id=".$ds->row["id_parent"]." order by id";
	$dr->open($sql);
	while(!$dr->eof)
	{
		if($dr->row["status"])
		{
			if($dr->row["refund"] == 1)
			{
				$invoice_number = word_lang("Refund money").": #".$global_settings["credit_notes_prefix"].$dr->row["invoice_number"];
				$link_class="style='color:red'";
			}
			else
			{
				$invoice_number = "#".$global_settings["invoice_prefix"].$dr->row["invoice_number"];
				$link_class="";
			}
			
			
			?>
			<a href="invoice.php?id=<?=$dr->row["invoice_number"]?>" <?=$link_class?>>
			 <?=$invoice_number?></a><br>
			<?
		}
		
		$dr->movenext();
	}
}
?>
</td>
</tr>
<?
$tr++;
$ds->movenext();
}
?>
</table>

<?}?>
<br><br><br>