<?if(!defined("site_root")){exit();}?>
<?
$product_name="";
$product_total=0;




if($_SESSION["billing_type"]=="credits")
{
	$sql="select price,title,days from credits where id_parent=".(int)$_SESSION["billing_id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$product_name=$rs->row["title"];
		$product_total=$rs->row["price"];
	}
}

if($_SESSION["billing_type"]=="subscription")
{
	$sql="select price,title from subscription where id_parent=".(int)$_SESSION["billing_id"];
	$rs->open($sql);
	if(!$rs->eof)
	{
		$product_name=$rs->row["title"];
		$product_total=$rs->row["price"];
	}
}

$product_subtotal=$product_total;
$product_discount=0;
$product_shipping=0;
$product_tax=0;


//Taxes
$taxes_info=array();
order_taxes_calculate($product_subtotal,false,$_SESSION["billing_type"]);
$taxes_text="(".$taxes_info["text"].")";
$product_tax=$taxes_info["total"];



//Discount
$discount_text="";
if(isset($_SESSION["coupon_code"]))
{
	$discount_info=array();
	order_discount_calculate($_SESSION["coupon_code"],$product_subtotal);
	$product_discount=$discount_info["total"];
	$discount_text=$discount_info["text"];
}


$product_total=$product_subtotal+$product_shipping+$product_tax*$taxes_info["included"]-$product_discount;

$_SESSION["product_total"]=$product_total;
$_SESSION["product_subtotal"]=$product_subtotal;
$_SESSION["product_tax"]=$product_tax;
$_SESSION["product_discount"]=$product_discount;
?>




<table border='0' cellpadding='0' cellspacing='0' class='profile_table' width='100%'>
<tr>
<th width='50%'><b><?=word_lang("Items")?></b></th>
<th><b><?=word_lang("quantity")?></b></th>
<th><b><?=word_lang("price")?></b></th>
<th><b><?=word_lang("total")?></b></th>
</tr>


<tr>
<td><b><?=$product_name?></b></td>
<td>1</td>
<td><?=currency(1,false).float_opt($product_subtotal,2)." ".currency(2,false)?></td>
<td><?=currency(1,false).float_opt($product_subtotal,2)." ".currency(2,false)?></td>
</tr>

<tr>
	<td><b><?=word_lang("subtotal")?></b></td>
	<td></td>
	<td></td>
	<td><?=currency(1,false).float_opt($product_subtotal,2)." ".currency(2,false)?></td>
	</tr>
	<tr>
	<td><b><?=word_lang("discount").$discount_text?></b></td>
	<td></td>
	<td></td>
	<td><?=currency(1,false).float_opt($product_discount,2)." ".currency(2,false)?></td>
	</tr>
	<tr>
	<td><b><?=word_lang("taxes")?> <?=$taxes_text?></b></td>
	<td></td>
	<td></td>
	<td><?=currency(1,false).float_opt($product_tax,2)." ".currency(2,false)?></td>
	</tr>
	<tr class="snd">
	<td><b><?=word_lang("total")?></b></td>
	<td></td>
	<td></td>
	<td><?=currency(1,false).float_opt($product_total,2)." ".currency(2,false)?></td>
	</tr>

	</tr>
	</table>