<? include("../function/db.php");?>
<?
//Check access
admin_panel_access("orders_commission");

$bank_info="";
if($_POST["method"]=="bank" and @$_POST["bank_name"]!="" and @$_POST["bank_account"]!="")
{
	$bank_info="\n".result(@$_POST["bank_name"]).": ".result(@$_POST["bank_account"]);
}

$sql="insert into commission (total,user,orderid,item,publication,types,data,gateway,description,status) values (".(-1*$_POST["total"]).",".(int)$_POST["user"].",0,0,0,'refund',".mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")).",'".result($_POST["method"])."','".result($_POST["description"]).$bank_info."',0)";
$db->execute($sql);

$product_type="payout_seller";
$link_back=surl.site_root."/admin/commission/index.php?d=2";


include("gateways.php");
?>
