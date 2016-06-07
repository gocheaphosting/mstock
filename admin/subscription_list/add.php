<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_subscription");

$sql="select login,name,address,country,lastname,city,zipcode from users where login='".result($_POST["user"])."'";
$rs->open($sql);
if(!$rs->eof)
{
	$sql="select id_parent,title,price,days,content_type,bandwidth,priority,bandwidth_daily from subscription where id_parent=".(int)$_POST["subscription"];
	$ds->open($sql);
	if(!$ds->eof)
	{
		$subtotal=$ds->row["price"];
		$discount=0;
		$taxes=0;
		$total=$subtotal+$taxes-$discount;

		$sql="insert into subscription_list (title,data1,data2,user,approved,bandwidth,bandwidth_limit,subscription,subtotal,discount,taxes,total,billing_firstname,billing_lastname,billing_address,billing_city,billing_zip,billing_country,bandwidth_daily,bandwidth_daily_limit,bandwidth_date) values ('".$ds->row["title"]."',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",".(mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))+3600*24*$ds->row["days"]).",'".result($_POST["user"])."',1,0,".$ds->row["bandwidth"].",".(int)$_POST["subscription"].",".$subtotal.",".$discount.",".$taxes.",".$total.",'".$rs->row["name"]."','".$rs->row["lastname"]."','".$rs->row["address"]."','".$rs->row["city"]."','".$rs->row["zipcode"]."','".$rs->row["country"]."',0,".$ds->row["bandwidth_daily"].",0)";
		$db->execute($sql);
	}
}

$db->close();

header("location:index.php");
?>
